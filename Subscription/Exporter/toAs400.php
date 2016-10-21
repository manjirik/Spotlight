<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 20.10.15
 * Time: 13:19
 */

namespace Spotlight\Subscription\Exporter;

use Spotlight\Subscription\Entity\SubscriptionIssueBased;

class toAs400
{
    public static function translate(SubscriptionIssueBased $subscription)
    {
        $subscription_type = $subscription->subscriptionType()->getAs400Value();
        $werbeweg = $subscription->werbeweg()->getAs400Value();

        $return = array();

        if (array_key_exists('ABO-ART1', $subscription_type)) {
            $return['ABO-ART1'] = $subscription_type['ABO-ART1'];
        }

        if (array_key_exists('ABO-ART2', $subscription_type)) {
            $return['ABO-ART2'] = $subscription_type['ABO-ART2'];
        }

        if (array_key_exists('WB-TRÄGER', $werbeweg)) {
            $return['WB-TRÄGER'] = $werbeweg['WB-TRÄGER'];
        }

        if (array_key_exists('WB-WEG', $werbeweg)) {
            $return['WB-WEG'] = $werbeweg['WB-WEG'];
        }

        return $return;
    }
}