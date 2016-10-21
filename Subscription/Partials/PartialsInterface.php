<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 20.10.15
 * Time: 09:31
 */

namespace Spotlight\Subscription\Partials;


interface PartialsInterface
{

    public function getAs400Value();

    public function toArray();

}