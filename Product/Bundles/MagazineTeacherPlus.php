<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 29.10.15
 * Time: 10:38
 */

namespace Spotlight\Product\Bundles;


/**
 * Class MagazineTeacherPlus
 * @package Spotlight\Product\Bundles
 */
class MagazineTeacherPlus extends Magazine
{
    /**
     * @param string $brandid
     * @param string $format
     */
    public function __construct(
        $brandid,
        $format,
        $params = array()
    )
    {
        parent::__construct($brandid, $format, $params);
        $this->addVariant('teacher');
        $this->addVariant('plus');
    }
}
