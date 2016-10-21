<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 30.10.15
 * Time: 13:21
 */

namespace Spotlight\Product\Bundles;

use Spotlight\Product\Entity\Product;

/**
 * Class Dalango: used to create a subscribable dalango product
 *
 * @package Spotlight\Product\Bundles
 */
class Dalango extends GenericBundle
{
    /**
     * pass in the dalango language id (71,72,...)
     *
     * @param string Product $languageid
     */
    public function __construct($languageid)
    {
        $mainproduct = new Product($languageid, 'onlineproduct', 'dalango', 'access');
        parent::__construct($mainproduct);
    }
}