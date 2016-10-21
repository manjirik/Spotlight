<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 15:41
 */

namespace Spotlight\Subscription\Partials;


class Probeabo implements PartialsInterface
{

    protected $type;
    protected $subtype;
    protected $name;
    protected $paid;

    public function __construct() // as a default, we have minimal 1 issue for free...
    {

        $this->type = 'P';
        //$this->subtype = $subtype;
        $this->name = 'PA';
        $this->paid = 0;
    }

    public function getAs400Value()
    {
        return array(
            'ABO-ART1' => $this->type,
            'ABO-ART2' => $this->subtype + $this->paid,
        );
    }

    public function setAs400Value($ABO_ART1, $ABO_ART2)
    {
        $this->type = $ABO_ART1;
        $this->subtype = $ABO_ART2;
    }



    public function setFreeIssues($n)
    {
        $this->subtype = (string)$n;
    }

    public function setPaidIssues($n)
    {
        $this->paid = (string)$n;
    }



    public function toArray()
    {
        return array(
            'type' => $this->type,
            'subtype' => $this->subtype,
            'name' => $this->name,
            'displayname' => $this->displayName(),
        );
    }

    public function displayName()
    {
        //if(!$this->subtype) $this->subtype = 1;
        $num_iss = intval($this->subtype) + (int)$this->paid;
        if (intval($num_iss) == 1) {
            return "Probeabo (1 Monat)";
        } else {
            return "Probeabo ($num_iss Monate)";
        }
    }


}