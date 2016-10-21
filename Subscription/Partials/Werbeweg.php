<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 20.10.15
 * Time: 11:30
 */

namespace Spotlight\Subscription\Partials;


class Werbeweg implements PartialsInterface
{

    protected $wwt;
    protected $www;

    public function __construct($ww = '')
    {
        if ($ww != '') {
            $w = explode('.', $ww);
            $this->wwt = $w[0];
            $this->www = $w[1];
        }
    }

    public function getAs400Value()
    {
        return array(
            'WB-TRÄGER' => $this->wwt,
            'WB-WEG' => $this->www,
        );
    }

    public function toArray()
    {
        if(!empty($this->wwt) && !empty($this->www))
            $w = $this->wwt. '.' . $this->www ;
        else $w = '';
        return trim($w);
    }

}