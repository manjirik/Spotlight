<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 10:25
 */

namespace Spotlight\Product\Entity;

/**
 * create a product by it's unique ID
 *
 * @package Spotlight\Product\Entity
 */
class ProductById extends Product
{

    /**
     * the unique product ID
     *
     * @param $id
     */
    public function __construct($id)
    {

        $issuebased = false;

        $props = explode('-', $id);

        $brandid = $props[0];
        $type = $props[1];
        $variant = $props[2];
        $format = $props[3];
        $issuenr = $props[4];

        // TODO: Error handling. if more than 5 props, it means, that it's NOT a base Product, but a bundle....
        if (count($props) > 5) {

        }

        if (substr($brandid, 0, 1) == '9') {
            if ($type == '03') {
                $variant = '06';
                $format = '03';
            }
            if ($format == '03') {
                $variant = '06';
                $type = '03';
            }
            $issuebased = true;
        }
        if (substr($brandid, 0, 1) == '7') {
            $type = '03';
            $variant = '07';
            $format = '03';
        }

        // create the product
        parent::__construct($brandid, $type, $variant, $format);

        //if it's issuebased, also set the issuenr
        if ($issuebased) {
            if ($issuenr == '999999') { //dummy value, dont set issuenr
                //do nothing;
            } else {
                $this->setIssueNr($issuenr);
            }
        }

    }
}