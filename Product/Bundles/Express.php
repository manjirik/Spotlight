<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 30.10.15
 * Time: 13:17
 */

namespace Spotlight\Product\Bundles;


use Spotlight\Product\Entity\Product;

/**
 * Class Express: used to create a subscribable express product
 * @package Spotlight\Product\Bundles
 */
class Express extends GenericBundle
{
    /**
     * no parameters needed (express is always spotlight...)
     *
     * might change in future
     *
     */
    public function __construct()
    {
        $mainproduct = new Product('spotlight', 'audioproduct', 'express', 'download');
        parent::__construct($mainproduct);
    }
}