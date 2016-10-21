<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 30.10.15
 * Time: 13:08
 */

namespace Spotlight\Product\Bundles;

use Spotlight\Product\Entity\Product;

/**
 * Class AudioTrainer: used to create a subscribable audio product
 *
 * @package Spotlight\Product\Bundles
 */
class AudioTrainer extends GenericBundle
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
        $format
    )
    {
        $mainproduct = new Product($brandid, 'audioproduct', 'standard', $format);
        parent::__construct($mainproduct);
    }
}