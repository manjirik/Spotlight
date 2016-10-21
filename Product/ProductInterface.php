<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 29.10.15
 * Time: 14:13
 */

namespace Spotlight\Product;

interface ProductInterface
{
    public function sku();

    public function brand();

    public function productType();

    public function productVariant();

    public function productFormat();

    public function issuenr();

    public function evt();

    public function frequency();

    public function getImage();

    public function isSubscribable();

    public function toArray();

}
