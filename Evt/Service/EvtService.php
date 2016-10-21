<?php

namespace Spotlight\Evt\Service;

use DateTime;
use Spotlight\Brand\Entity\Brand;
use Spotlight\ProductType\Entity\ProductFormat;
use Spotlight\ProductType\Entity\ProductVariant;

/**
 * Class EvtService
 *
 * The EvtService is a helper class used to calculate EVT relevant data
 * You can use it to calculate the EVT-Date for a given ISSUE or vice versa.
 * The calculation depends on various parameters (brand, producttype, ...)
 *
 * It's used within the {@see \Spotlight\Product\Entity\Product} but can be used as a standalone service also
 *
 * @package Spotlight\Evt\Service
 */
class EvtService
{
    /**
     * container for Brand object
     *
     * @var \Spotlight\Brand\Entity\Brand
     */
    protected $brand;

    /**
     * container for product type object
     *
     * @var \Spotlight\ProductType\Entity\ProductFormat
     */
    protected $product_format;

    /**
     * container for product variant object
     *
     * @var \Spotlight\ProductType\Entity\ProductVariant
     */
    protected $product_variant;

    /**
     * a Datetime object all calculation will be based on that
     *
     * @var DateTime
     */
    protected $time;

    /**
     * Ths string representation of an issue.
     *
     * issuenumber in Format YYYYXX
     *
     * @var string
     */
    protected $issue;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->time = new DateTime();
    }

    /**
     * Calculates the ISSUEnumber given a partial product configuration AND a timestamp
     *
     * We only inject the configuration parts of a product not the product itself, because the EVT Service is injected into the product
     * (avoiding circular reference)
     *
     * @param Brand $brand
     * @param ProductFormat $product_format
     * @param ProductVariant $product_variant
     * @param int $ts unix timestamp
     * @return string $issue (Issuenumber in format YYYYXX)
     */
    public function getIssueByDate(Brand $brand, ProductFormat $product_format, ProductVariant $product_variant, $ts)
    {

        $this->brand = $brand;
        $this->product_format = $product_format;
        $this->product_variant = $product_variant;
        $this->time->setTimestamp($ts);

        //create year, month, day from timestamp $ts
        $y = $this->time->format('Y');  // year 4 digits
        $m = $this->time->format('n'); // month (1 to 12)
        $d = $this->time->format('j'); // day of month (1 to 31)

        // business spotlight
        if ($this->brand->id() == '96') {
            $bm = ceil($m / 2) * 2;
            if ($m == 1) {
                $bm = 12;
                $y -= 1;
            }
            $change = $this->calcChangeDate($y, $bm);
            $is = $bm / 2;
            if ($ts >= $change) { // issue des nächsten Monats
                if ($is == 6) {
                    $is = 1;
                    $y += 1;
                } else {
                    $is += 1;
                }
            }
            $curr_issue = (string)$y . sprintf("%02d", $is);
        } // spotlight express
        elseif ($this->brand->id() == '91' && $this->product_variant->name() == 'express') {
            $increment = ($d < 16) ? 1 : 2; // increment = 1 for 1-15, 2 for 16-31
            $curr_nr = ((intval($m) - 1) * 2) + $increment;
            $curr_nr = sprintf("%02d", $curr_nr);

            $curr_issue = $y . $curr_nr; // issue nr. like 201507
        } // everything else
        else {
            $change = $this->calcChangeDate($y, $m);
            if ($ts >= $change) { // issue des nächsten Monats
                if ($m == 12) {
                    $m = 0;
                    $y += 1;
                }
                $curr_issue = (string)$y . sprintf("%02d", $m + 1);
            } else { // issue des aktuellen Monats
                $curr_issue = (string)$y . sprintf("%02d", $m);
            }
        }

        return $curr_issue;

    }

    /**
     * calculates the changeDate == EVT-Date in given month
     *
     * used to decide if it should be the issue from the last month (before EVT) or the actual month issue (after EVT)
     *
     * @param $year
     * @param $monthNum
     * @return int unixtimestamp
     */
    private function calcChangeDate($year, $monthNum)
    {

        $dow = "last Wednesday of ";
        if ($monthNum == 12) $dow = "third Wednesday of ";
        $date = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $date->format('F');

        $time = strtotime($dow . $monthName . ' ' . $year);
        $time = $this->tweekTime($year, $monthNum, $time); // respect some edge cases
        if ($this->_checkSpecialEvtDate($year, $monthNum)) $time = $this->_checkSpecialEvtDate($year, $monthNum);// respect even more edge cases (hardcoded calendar special dates)
        if ($this->product_format->name() == 'download') $time -= 86400;

        return $time;

    }

    /**
     * needed to finetune cacluation (especially for christmas...)
     *
     * @param $year
     * @param $monthNum
     * @param $time
     * @return int unixtimestamp
     * @internal param $monthName
     */
    private function tweekTime($year, $monthNum, $time)
    {

        $oneweek = 60 * 60 * 24 * 7;

        if ($monthNum == 12) {
            $christmas = strtotime("$year-$monthNum-24");
            if (($christmas - $time) < $oneweek) $time -= $oneweek;
        }

        return $time;

    }

    /**
     * helper function: check for special EVT-Fates that dont follow the normal calculation rules
     *
     * @param $year
     * @param $month
     * @return bool
     */
    private function _checkSpecialEvtDate($year, $month)
    {
        $special_dates = array(
            '2016' => array(
                '11' => strtotime("2016-11-23"),
            )
        );
        if (isset($special_dates[$year][$month])) return $special_dates[$year][$month];
        return false;
    }

    /**
     * helper function: calculate the NEXT Issue nr. (needed for calculation of first deliverable issue nr for physical products
     *
     * @param Brand $brand
     * @param ProductVariant $product_variant
     * @param string $issue
     *
     * @return string isuuenr
     */
    public function nextIssueNr(Brand $brand, ProductVariant $product_variant, $issue)
    {
        $year = substr($issue, 0, 4);
        $nr = substr($issue, 4, 2);
        $lastnr = 12;
        if ($brand->id() == '96') {
            $lastnr = 6;
        } elseif ($brand->id() == '91' && $product_variant->name() == 'express') {
            $lastnr = 24;
        }
        if ((int)$nr < $lastnr) {
            $nr += 1;
        } else {
            $nr = 1;
            $year += 1;
        }
        $nr = sprintf("%02d", $nr);
        return $year . $nr;
    }

    public function prevIssueNr(Brand $brand, ProductVariant $product_variant, $issue)
    {
        $year = substr($issue, 0, 4);
        $nr = substr($issue, 4, 2);
        $lastnr = 12;
        if ($brand->id() == '96') {
            $lastnr = 6;
        } elseif ($brand->id() == '91' && $product_variant->name() == 'express') {
            $lastnr = 24;
        }
        if ($nr == '01') {
            $year -= 1;
            $nr = $lastnr;
        } else {
            (int)$nr -= 1;
        }
        $nr = sprintf("%02d", $nr);
        return $year . $nr;
    }

    /**
     * Calculates the DATE given a partial product configuration AND an issue number
     *
     * We only inject the configuration parts of a product not the product itself, because the EVT Service is injected into the product
     * (avoiding circular reference)
     *
     * @param Brand $brand
     * @param ProductFormat $product_format
     * @param ProductVariant $product_variant
     * @param $issue
     * @return int unixtimestamp: EVT-Date of the given Issue
     */
    public function getEvtDateByIssue(Brand $brand, ProductFormat $product_format, ProductVariant $product_variant, $issue)
    {

        $this->brand = $brand;
        $this->product_format = $product_format;
        $this->product_variant = $product_variant;
        $this->issue = $issue;

        //$digital = $this->productFormat->name() == 'download' ? TRUE : FALSE;

        // spotlight express
        if ($this->brand->id() == '91' && $this->product_variant->name() == 'express') {
            $year = substr($issue, 0, 4);
            $i = (int)substr($issue, 4, 2);
            $month = sprintf("%02d", ceil($i / 2));
            $day = ($i % 2) ? '1' : '16';
            $date = $year . '-' . $month . '-' . $day;
            return strtotime($date);
        } // everything else
        else {
            return $this->guessEvtDate();
        }
    }

    /**
     * helper funtion: get the month out of issuenumber (brandspecific)
     *
     * (and respects month = 12 for issue XXXX01)
     *
     * @return int unixtimestamp
     */
    private function guessEvtDate()
    {

        $year = substr($this->issue, 0, 4); // year from issuenr
        $i = (int)substr($this->issue, 4, 2); // issuenr

        if ($this->brand->id() == '96') {
            $monthNum = ($i - 1) * 2;
        } else {
            $monthNum = $i - 1;
        }
        // for issue 1: take third wednesday in dezember of last year
        if ($i == 1) {
            $year = (int)$year - 1;
            $monthNum = 12;
        }

        return $this->calcChangeDate($year, $monthNum);

    }

    /**
     * [getFid description]
     * @param  [type] $sku   [description]
     * @param  [type] $issue [description]
     * @param  [type] $type  [description]
     * @return [type]        [description]
     */
    /*
     * this needs to be moved from sdk to drupal module implementation
     */
    /*
    public function getFid($sku, $issue, $type) {

      $typemap = array(
        'express' => 'field_as3dwl',
        'audio' => 'field_as3dwl',
        'magazine' => 'field_epaper',
        'plus' => 'field_plus',
        'teacher' => 'field_lehrer',
      );

      $result = db_query('SELECT synctag as issue, fid, status FROM {download_issues} WHERE sku = :sku AND synctag = :issue AND itemtype = :type ORDER BY synctag DESC LIMIT 1', array(':sku' => $sku, ':issue' => $issue, ':type' => $typemap[$type]))->fetchObject();
      if($result) {
        return $result;
      } else {
        throw new Exception('not published');
      }
    }
    */


}