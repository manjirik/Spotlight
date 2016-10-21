<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 14:39
 */

namespace Spotlight\Subscription\Entity;

use DateInterval;
use DateTime;
use Spotlight\Evt\Service\EvtService;
use Spotlight\Product\Bundles\GenericBundle;
use Spotlight\Product\Entity\ProductById;
use Spotlight\stdSpotlight;
use Spotlight\Subscription\Partials\Festabo;
use Spotlight\Subscription\Partials\Probeabo;
use Spotlight\Subscription\Partials\Werbeweg;
use Spotlight\Subscription\SubscriptionBase;

/**
 * Class SubscriptionIssueBased used for all subscriptions that are issuebased
 *
 * @inheritdoc
 *
 * @package Spotlight\Subscription\Entity
 */
class SubscriptionIssueBased extends SubscriptionBase
{

    /**
     * duration in month of the first PAID interval
     *
     * e.g. 12 for Jahresabo, 2 for Miniabo
     *
     * @var int interval in month
     */
    protected $first_interval;

    /**
     * duration in month of the first prolong interval
     *
     * prolong interval starts directly AFTER first_interval
     *
     * e.g. 12 for Jahresabo, 12 for Miniabo
     *
     * for POs prolong_interval is set to 0
     *
     * @var int
     */
    protected $prolong_interval;

    /**
     * (optional) duration in month BEFORE for first PAID interval
     *
     * can be set for special offers (4 für 3) oder for all testoffers
     *
     * @var
     */
    protected $free_issues;

    /**
     * Subscription type can be either Festabo or Probeabo
     *
     * @var Festabo|Probeabo
     */
    protected $subscription_type;

    /**
     * (optional) The AS/400 werbeweg
     *
     * @var Werbeweg
     */
    protected $werbeweg;

    /**
     * container for products (bundles!!!)
     *
     * @var GenericBundle|stdSpotlight
     */
    protected $bundle;

    /**
     * normally the sku comes from the bundle. in some cases it might be neccessary to overwrite it (specail marketing offers...)
     *
     * @var
     */
    protected $sku;

    /**
     * is this subscription a 'Geschenkabo'?
     *
     * @var bool
     */
    protected $gift_subscription;

    /**
     * is this subscription a student subscription (special price)
     *
     * @var bool
     */
    protected $student_subscription;

    /**
     * container for an (bundle-)independent EVT-calculator
     *
     * apart from the standard EVT-Information contained in the Bundle it might be necessary to
     * calculate separate dates/issues (at least for physical products:
     * what is the next issuenr that can be delivered (after subscription start)?
     *
     * @var EvtService
     */
    protected $evtservice;

    protected $now;


    /**
     * with the three given parameters, any kind of subscription period can be modelled
     *
     * @param integer $firstinterval :
     * The first PAID intervall, e.g Jahresabo --> 12, Miniabo --> 2
     *
     * For Probeabos it must be 0 (no first paid interval)
     *
     * @param integer $prolonginterval :
     * after the first PAID interval, the subscription should be prolonged for that amount of month
     *
     * e.g. Jahresabo --> 12
     *
     * For PO this must be 0
     *
     * For NO this must contain the interval in month
     *
     * @param int|null $start (unix timestamp)
     * (optional) set to any valid timestamp: Startdate of the subscription
     *
     * if not set, the current time "now" will be used
     *
     */
    public function __construct($firstinterval, $prolonginterval, $start = NULL)
    {
        $this->first_interval = $firstinterval;
        $this->prolong_interval = $prolonginterval;

        $this->start = new DateTime("now");
        $this->now = new DateTime("now");

        if ($start) {
            $this->start->setTimestamp($start);
        }

//        $this->prolong = new DateTime();
//        $this->prolong->setTimestamp($this->start->getTimestamp());
//        $this->prolong->add(new DateInterval("P" . $firstinterval . "M"));
//
//        if (intval($prolonginterval) == 0) {
//            $this->end = $this->prolong;
//            $this->prolong = false;
//            $this->subscription_type = new Probeabo();
//        } else {
//            $this->end = false;
//            $this->subscription_type = new Festabo($firstinterval);
//        }
        $this->calculateProlongEnd();

        //$this->setFreeIssues($freeissues);
        $this->werbeweg = new Werbeweg();
        $this->gift_subscription = false;
        $this->evtservice = new EvtService();
        $this->bundle = new stdSpotlight();
    }

    private function calculateProlongEnd()
    {
        $this->prolong = new DateTime();
        $this->prolong->setTimestamp($this->start->getTimestamp());
        $this->prolong->add(new DateInterval("P" . (string)((int)$this->first_interval + (int)$this->free_issues) . "M"));

        if (intval($this->prolong_interval) == 0) {
            $this->end = $this->prolong;
            $this->prolong = false;
            $this->subscription_type = new Probeabo();
            $this->subscription_type->setPaidIssues($this->first_interval);
            //if(!$this->free_issues && $this->first_interval == 0) $this->free_issues = 1;
            $this->subscription_type->setFreeIssues($this->free_issues);
        } else {
            $this->end = false;
            $this->subscription_type = new Festabo($this->first_interval);
        }
    }

    /**
     * set amount of free issues (in month)
     *
     * @param int $n
     */
    public function setFreeIssues($n)
    {
        $this->free_issues = $n;
        $this->calculateProlongEnd();
        $this->subscription_type->setFreeIssues($n);
//        $this->subscription_type->setFreeIssues($n);
//        if ($this->subscription_type->getAs400Value()['ABO-ART1'] == 'F') {
//            $n += $this->first_interval;
//            $this->prolong = new DateTime("+ $n month");
//        } elseif ($this->subscription_type->getAs400Value()['ABO-ART1'] == 'S') {
//            $n += $this->first_interval;
//            $this->prolong = new DateTime("+ $n month");
//        } else {
//            $this->end = new DateTime("+ $n month");
//        }
    }

    /**
     * set the ww
     *
     * Format wwt.ww (like used in any online context...)
     *
     * * wwt == as400-Werbeträger
     *
     * * ww == as400-werbeweg
     *
     * @param string $werbeweg
     */
    public function setWerbeweg($werbeweg)
    {
        $this->werbeweg = new Werbeweg($werbeweg);
    }

    /**
     * add a product / Bundle to the subscription
     *
     * @param GenericBundle $bundle
     */
    public function setBundle(GenericBundle $bundle)
    {
        //$bundle->setDate($this->startDate()->getTimestamp());
        $this->bundle = $bundle;
    }





//
//    /**
//     * @param $key
//     * @return array
//     */
//    public function getAs400Value($key)
//    {
//        if (is_object($this->{$key})) {
//            return $this->{$key}->getAs400Value();
//        } else {
//            return array();
//        }
//    }

    /**
     * returns the start date of the subscription
     *
     * @return DateTime
     */
    public function startDate()
    {
        return $this->start;
    }

    /**
     * (optional) sets the sku, e.g. for special marketing offers with special SKUs
     *
     * @param int $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * (optional) sets a special displayname
     *
     * if this function is used, the automatic generated display name will be overwritten
     *
     * @param string $displayname
     */
    public function setDisplayName($displayname)
    {
        $this->displayname = $displayname;
    }

    /**
     * (optional) gift subscription can be set to TRUE/FALSE
     *
     * @param boolean $isgift
     */
    public function setGift($isgift)
    {
        $this->gift_subscription = $isgift;
    }

    /**
     * returns the product frequency based on the bundle
     *
     * @return int
     */
    public function frequency()
    {
        return $this->bundle->frequency();
    }

    /**
     * serialized infos
     *
     * @return array
     */
    public function toArray()
    {
        $dd = new DateTime();
        $dd->setTimestamp($this->firstDeliveryDate());
        return array(
            //'subscription_start' => $this->startDate(),
            //'subscription_prolong' => $this->prolongDate(),
            //'subscription_end' => $this->endDate(),
            'subscription_type' => $this->subscriptionType()->toArray(),
            'werbeweb' => $this->werbeweg()->toArray(),
            'sku' => $this->sku(),
            'displayname' => $this->displayName(),
            'gift_subscription' => $this->giftSubscription(),
            'student_subscription' => $this->studentSubscription(),
            //'first_issue' => $this->firstDeliveryIssuenr(),
            //'current_issue' => $this->currentIssueNr(),
            //'last_issue' => $this->lastIssueNr(),
            'bundle' => $this->bundle->toArray(),
            'bundleinfoimg' => $this->bundle->getImage(),

        );
    }

    /**
     * returns the (EVT-)date of the first deliverable Issue depending on the product Format
     *
     * the rule is:
     *
     * * all digital subscriptions deliver immediately. So the delivery date is the EVT-Date of the CURRENT available issue nr.
     *
     * * all physical subscriptions deliver with the next available issuenr. so the delivery date is the EVT-Date of the NEXT available issue nr
     *
     * @return int unix timestamp
     */
    public function firstDeliveryDate()
    {
        if (count($this->bundle->toArray())) {
            if ($this->bundle->productFormat()->name() == 'physical') {
                $issue = $this->evtservice->nextIssueNr($this->bundle->brand(), $this->bundle->productVariant(), $this->bundle->issuenr());
            } else {
                $issue = $this->bundle->issuenr();
            }
            return $this->evtservice->getEvtDateByIssue(
                $this->bundle->brand(),
                $this->bundle->productFormat(),
                $this->bundle->productVariant(),
                $issue
            );
        } else {
            return 0;
        }
    }

    /**
     * returns the prolong date of the subscription
     *
     * @return DateTime
     */
    public function prolongDate()
    {
        return $this->prolong;
    }

    /**
     * returns the end date of the subscription
     *
     * @return DateTime
     */
    public function endDate()
    {
        return $this->end;
    }

    /**
     * returns the subscription type object
     *
     * @return Festabo|Probeabo
     */
    public function subscriptionType()
    {
        return $this->subscription_type;
    }

    /**
     * returns the ww. (ww needs to be set manually before)
     *
     * @return Werbeweg
     */
    public function werbeweg()
    {
        return $this->werbeweg;
    }

    /**
     * returns either the manually set sku, or the bundle sku
     *
     * @return int
     */
    public function sku()
    {
        if ($this->sku) {
            return $this->sku;
        } else {
            return $this->bundle()->sku();
        }
    }

    /**
     * returns the current bundled product object, or the placeholder stdSpotlight
     *
     * @return GenericBundle|stdSpotlight
     */
    public function bundle()
    {
        if (!empty($this->bundle)) {
            return $this->bundle;
        }
        return new stdSpotlight();
    }

    /**
     * return the display name
     *
     * @return string
     */
    public function displayName()
    {
        if ($this->displayname) {
            return $this->displayname;
        } else {
            return $this->subscriptionType()->displayName() . ' ' . $this->bundle()->displayName();
        }
    }

    /**
     * is this a gift subscription?
     *
     * @return boolean
     */
    public function giftSubscription()
    {
        return $this->gift_subscription;
    }

    /**
     * is this a student subscription?
     *
     * @return bool
     */
    public function studentSubscription()
    {
        return $this->student_subscription;
    }

    /**
     * return the issuenr (Format YYYYXX) of the first deliverable Issue depending on the product Format
     *
     * the rule is:
     *
     * * all digital subscriptions deliver immediately. So the delivery issuenr is the CURRENT available issuenr.
     *
     * * all physical subscriptions deliver with the next available issuenr. so the delivery issuenr is NEXT available issuenr
     *
     * @return string
     */
    public function firstDeliveryIssuenr()
    {
        if (count($this->bundle->toArray())) {
            if ($this->bundle->productFormat()->name() == 'physical') {
                $issue = $this->evtservice->nextIssueNr($this->bundle->brand(), $this->bundle->productVariant(), $this->bundle->issuenr());
            } else {
                $issue = $this->bundle->issuenr();
            }
            return $issue;
        } else {
            return '';
        }
    }

    //TODO: maybe move that functionalty to a helper class --> inject the subscription get out the issues...

    public function currentIssueNr()
    {
        $current_issue = $this->evtservice->getIssueByDate($this->bundle->brand(), $this->bundle->productFormat(), $this->bundle->productVariant(), $this->now->getTimestamp());
        if ($this->firstDeliveryIssuenr() <= $current_issue) {
            if ($this->lastIssueNr()) {
                if ($this->lastIssueNr() <= $current_issue) return false;
            }
            return $current_issue;
        }
        return false;
    }

    public function lastIssueNr()
    {
        if ($this->endDate()) {
            $issue = $this->evtservice->getIssueByDate($this->bundle->brand(), $this->bundle->productFormat(), $this->bundle->productVariant(), $this->endDate()->getTimestamp());
            return $this->evtservice->prevIssueNr($this->bundle->brand(), $this->bundle->productVariant(), $issue);
        } else {
            return false;
        }
    }

    public function includedIssues()
    {
        $issues = array();
        $issue = $this->firstDeliveryIssuenr();
        $stopissue = $this->lastIssueNr();
        if ($this->currentIssueNr()) $stopissue = $this->currentIssueNr();
        while ($issue <= $stopissue) {
            if ($this->bundle) {
                $product = new ProductById($this->bundle->mainProduct()->id());
                $product->setIssueNr($issue);
                $issues[$product->id()] = $product;
                if (count($this->bundle->addons())) {
                    foreach ($this->bundle->addons() as $addon) {
                        $product = new ProductById($addon->id());
                        $product->setIssueNr($issue);
                        $issues[$product->id()] = $product;
                    }
                }
            }
            $issue = $this->evtservice->nextIssueNr($this->bundle->brand(), $this->bundle->productVariant(), $issue);
        }
        return $issues;
    }

    public function nextIssueNr()
    {
        $next_issue = $this->evtservice->nextIssueNr($this->bundle->brand(), $this->bundle->productVariant(), $this->currentIssueNr());
        if ($this->firstDeliveryIssuenr() <= $next_issue && $next_issue <= $this->lastIssueNr()) return $next_issue;
        return false;
    }


}