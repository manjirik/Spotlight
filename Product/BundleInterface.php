<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 29.10.15
 * Time: 14:14
 */

namespace Spotlight\Product;


use Spotlight\Product\Entity\Product;

interface BundleInterface extends ProductInterface
{
    public function addAddon(Product $subproduct);

    public function removeAddon(Product $subproduct);

}