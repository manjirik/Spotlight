<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 26.10.15
 * Time: 15:24
 */

namespace Spotlight\Product\Bundles;

use Spotlight\Product\Entity\Product;
use Spotlight\Product\Entity\ProductBySku;

/**
 * Class Magazine
 *
 * Standard class to create a subscribable magazine. addons (like plus and Lehrerbeilage CAN be added/removed later)
 * We need that functionality for aboshop / mighty checkout. like: Asking for the teacher address in MC --> Magazine becomes a MagazineTeacher...
 *
 * All the subtypes can be used the same way and are exchangeable
 *
 * e.g.
 *
 * $a = new MagazinePlus(...);
 *
 * $b = new Magazine(...);
 *
 * $b->addVariant('plus);
 *
 * $c = new MagazineTeacherPlus(...);
 *
 * $c->removeVariant('teacher');
 *
 * $a == $b == $c
 *
 * (not really, as they are different classes, but they behave exactly the same and hold the same data...)
 *
 * @package Spotlight\Product\Bundles
 */
class Magazine extends GenericBundle
{

    /**
     * brand(id) and format(physical|download) needed.
     *
     * {@see \Spotlight\Products\Entity\Product}
     *
     * @param string $brandid
     * @param string $format
     */
    public function __construct(
        $brandid,
        $format,
        $params = array()
    )
    {
        $mainproduct = new Product($brandid, 'printproduct', 'standard', $format, $params);
        parent::__construct($mainproduct, $params);
    }

    /**
     * add an addon by identifier ('plus' or 'teacher')
     *
     * @param string $variant
     */
    public function addVariant($variant)
    {
        $subproduct = $this->createVariant($variant);
        if ($subproduct) {
            parent::addAddon($subproduct);
        }
    }

    private function createVariant($variant)
    {
        switch ($variant) {
            case 'teacher':
                $subproduct = new ProductBySku($this->mainproduct->sku(), 'teacher');
                break;
            case 'plus':
                $subproduct = new ProductBySku($this->mainproduct->sku(), 'plus');
                break;
            default:
                $subproduct = false;
                break;
        }
        return $subproduct;
    }

    /**
     * remove an addon by identifier ('plus' or 'teacher')
     *
     * @param $variant
     */
    public function removeVariant($variant)
    {
        $subproduct = $this->createVariant($variant);
        if ($subproduct) {
            parent::removeAddon($subproduct);
        }
    }

}
