<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 12:25
 */

namespace Spotlight\Product\Entity;


/**
 * creates a product by SKU and variant (optional)
 *
 * e.g.
 *
 * SKU = 91100 --> spotlight magazine
 *
 * variant = teacher --> Lehrerbeilage
 *
 * @package Spotlight\Product\Entity
 */
class ProductBySku extends Product
{
    /**
     * creates a product by SKU and variant (optional)
     *
     * SKU = 91100 --> spotlight magazine
     *
     * variant = teacher --> Lehrerbeilage
     *
     * @param string $sku
     * @param string $variant
     */
    public function __construct($sku, $variant = 'standard')
    {

        $brandid = substr($sku, 0, 2);
        $magazine_dalango = substr($sku, 0, 1);

        switch (substr($sku, 2, 4)) {
            case '100':
            case '101':
            case '102':
            case '103':
                if ($magazine_dalango == '9') {
                    $type = 'printproduct';
                    $format = 'physical';
                } elseif ($magazine_dalango == '7') {
                    $type = 'onlineproduct';
                    $format = 'access';
                    $variant = 'dalango';
                } else {
                    $type = 'undefined';
                    $format = 'undefined';
                }
                break;
            case '300':
                $type = 'audioproduct';
                $format = 'physical';
                break;
            case '500':
                $type = 'onlineproduct';
                $format = 'access';
                $variant = 'premium';
                break;
            case '600':
                $type = 'printproduct';
                $format = 'download';
                break;
            case '700':
                $type = 'audioproduct';
                $format = 'download';
                break;
            case '800':
                $type = 'audioproduct';
                $format = 'download';
                $variant = 'express';
                break;
            case '620':
                $type = 'printproduct';
                $format = 'download';
                $variant = 'international';

                break;
            default:
                $type = 'undefined';
                $format = 'undefined';

                break;

        }

        parent::__construct($brandid, $type, $variant, $format);

    }

}
