<?php

namespace Spotlight\Brand\Entity;

use Spotlight\Brand\BrandBase;
use Spotlight\File\Entity\Image;

/**
 * Class Brand
 *
 * helper class: instantiates the right Subclass, based on the brand ID or brand name
 *
 * @package Spotlight\Brand\Entity
 */
class Brand extends BrandBase
{
    /**
     * container for the image url
     *
     * @var url
     */
    protected $image;
    /**
     * container with params
     *
     * @var array
     */
    protected $params;

    /**
     * @param string|NULL $identifier
     */
    public function __construct($identifier = NULL, $params = array())
    {

        switch ((string)$identifier) {
            case '91':
            case 'spotlight':
                $this->brand = new BrandSpotlight();
                break;
            case '92':
            case 'ecoute':
                $this->brand = new BrandEcoute();
                break;
            case '93':
            case 'ecos':
                $this->brand = new BrandEcos();
                break;
            case '94':
            case 'adesso':
                $this->brand = new BrandAdesso();
                break;
            case '95':
            case 'spoton':
                $this->brand = new BrandSpotOn();
                break;
            case '96':
            case 'business-spotlight':
                $this->brand = new BrandBusinessSpotlight();
                break;
            case '97':
            case 'deutsch-perfekt':
                $this->brand = new BrandDeutschPerfekt();
                break;
            case '71':
            case '72':
            case '73':
            case '74':
            case '76':
            case '79':
                $this->brand = new BrandDalango($identifier);
                break;
            default:
                $this->brand = new BrandNone();
                break;
        }
        $this->setParams($params);

        $this->id = $this->brand->id();
        $this->displayname = $this->brand->displayName();
        $this->name = $this->brand->name();
        $this->claim = $this->brand->claim();
        $this->language = $this->brand->language();
        $this->teacheraddon = $this->brand->teacheraddon();
        $this->frequency = $this->brand->frequency();
        $this->params = $this->getParams();
    }

    /**
     * returns the brand as an object
     *
     * @return BrandSpotlight
     */
    public function brand()
    {
        return $this->brand;
    }
    /**
     * returns the image as an object
     *
     * @return Image
     */
    public function image()
    {
        $image_params = $this->getParams();
        $image_params['brand_id'] = $this->id;
        $image_params['name'] = 'brandimage';

        $image = new \Spotlight\File\Entity\Image($image_params);
        return $image;
    }

    /**
     * returns the image as an URL
     *
     * @return URL (string)
     */
    public function getImage()
    {
        $image = $this->image();
        $this->image = $image->url();
        return $this->image;
    }
    /**
     * returns params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * set params
     *
     * @return array
     */
    public function setParams($p)
    {
      if (is_array($p)) {
        $this->params = $p;
      }
    }

}
