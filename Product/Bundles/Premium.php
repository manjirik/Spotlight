<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 30.10.15
 * Time: 13:28
 */

namespace Spotlight\Product\Bundles;

use Spotlight\Product\Entity\Product;

/**
 * Class Premium
 * @package Spotlight\Product\Bundles
 */
class Premium extends GenericBundle
{
    /**
     * @param Product $brandid
     */
    public function __construct($brandid)
    {
        $mainproduct = new Product($brandid, 'onlineproduct', 'premium', 'access');
        parent::__construct($mainproduct);
    }
}