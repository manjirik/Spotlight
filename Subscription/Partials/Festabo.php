<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 19.10.15
 * Time: 16:20
 */

namespace Spotlight\Subscription\Partials;


class Festabo implements PartialsInterface
{
    protected $type;
    protected $subtype;
    protected $displayName;
    protected $name;

    public function __construct($firstinterval, $subtype = '')
    {

        $this->subtype = $subtype;
        $this->name = 'FA';

        switch ($firstinterval) {
            case 12:
                $this->type = 'F';
                $this->displayName = 'Jahresabo';
                break;
            case 2:
                $this->type = 'S';
                $this->displayName = 'Miniabo';
                break;
        }

    }

    public function getAs400Value()
    {
        return array(
            'ABO-ART1' => $this->type,
            'ABO-ART2' => $this->subtype
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

    //public function as400_type()
    //{
    //    return $this->type;
    //}

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
        if (empty($this->subtype)) return $this->displayName;
        if (intval($this->subtype) == 1) {
            return $this->displayName . " (1 Ausgabe gratis)";
        } else {
            return $this->displayName . " ($this->subtype Ausgaben gratis)";
        }
    }


}