<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 14:35
 */

namespace Spotlight\Subscription;

use DateTime;
use Spotlight\EntityBase;

/**
 * Class SubscriptionBase: used to create a subscription.
 *
 * creating every subscription has two mandatory steps:
 *
 * * create the subscription with the duration information and the type of subscription
 *
 * * add **ONE** subscribable product (this product can have as many addons as needed)
 *
 * So ONE subscribable product is ONE line item (cart-item) in mighty checkout
 *
 *
 * Then there are some optional steps, like overwrite default values (special Product naming for markting campaign, set the ww, etc.
 *
 * @package Spotlight\Subscription
 */
abstract class SubscriptionBase extends EntityBase
{

    /**
     * DateTime object: when does the subscription start?
     *
     * @var DateTime
     */
    protected $start;

    /**
     * DateTime object: when does the subscription prolong?
     *
     * it does respect free issues if they are included
     *
     * e.g. Jahresabo --> prolong = start + 12 month
     *
     * e.g. **Miniabo mit 1 Monat gratis testen** --> prolong = start + 1 month free + 2 month
     *
     * @var DateTime
     */
    protected $prolong;

    /**
     * DateTime object: when does the subscription end?
     *
     * normally a subscription doesn't have an end!!!
     *
     * The end date is set **only**, if:
     *
     * * the subscription is cancelled
     *
     * * it is an subscription with PO (which doesn't prolong...)
     *
     * @var DateTime
     */
    protected $end;

}