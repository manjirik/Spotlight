<?php
/**
 * Created by PhpStorm.
 * User: dspitzhorn
 * Date: 30.10.15
 * Time: 10:16
 */

namespace Spotlight\Product\Bundles;


use Spotlight\EntityBase;
use Spotlight\Product\BundleInterface;
use Spotlight\Product\Entity\Product;
use Spotlight\stdSpotlight;
use Spotlight\File\Entity\Image;

/**
 * Class GenericBundle: used to contruct ANY subscribable product
 *
 * @package Spotlight\Product\Bundles
 */
class GenericBundle extends EntityBase implements BundleInterface
{
    /**
     * image url
     *
     * @var URL
     */
    protected $image;
    /**
     * container with params
     *
     * @var array
     */
    protected $params;

    /**
     * container for the main/base product
     *
     * @var Product
     */
    protected $mainproduct;

    /**
     * array containing n subproducts of {@see \Spotlight\Products\Entity\ProductBySku}
     *
     * @var Product[]
     */
    protected $addons;

    /**
     * each bundle has at least one base/mainproduct
     *
     * @param Product $mainproduct
     */
    public function __construct(Product $mainproduct, $params = array())
    {
        $this->mainproduct = $mainproduct;
        $this->setParams($params);
    }

    /**
     * sets the bundle id.
     *
     * in the generic case we cannot know in each case how to create the id, therefore it CAN be set manually
     *
     * in the preset Bundles, the id will be created automatically
     *
     * @param string $identifier
     */
    public function setId($identifier)
    {
        $this->id = $identifier;
    }


    /**
     * add ANY subproduct
     *
     * it is possible to create new, not existing combinations with that function
     *
     * e.g. Spotlight magazine with dalango english
     *
     * @param Product $subproduct
     */
    public function addAddon(Product $subproduct)
    {
        $this->addons[$subproduct->id()] = $subproduct;
        ksort($this->addons);
        $this->image();

    }

    /**
     * remove the subproduct again
     *
     * @param Product $subproduct
     */
    public function removeAddon(Product $subproduct)
    {
        if (array_key_exists($subproduct->id(), $this->addons)) {
            unset($this->addons[$subproduct->id()]);
            ksort($this->addons);
        }
        $this->image();
    }

    /**
     * normally we would expect the sku to equal the mainproduct sku.
     *
     * for any special case, when that is not true, we can manually set it.
     *
     * @param int $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * returns the frequency of the mainproduct
     *
     * //TODO: for some combinations that could be invalid. e.g. imagine a bundle of spotlight and business sptolight magazine...
     *
     * @return int
     */
    public function frequency()
    {
        return $this->mainproduct->frequency();
    }

    /**
     * is this product subscribable? always true for bundles
     *
     * @return bool
     */
    public function isSubscribable()
    {
        return true;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id(),
            'sku' => $this->sku(),
            'brand' => $this->brand()->toArray(),
            'productType' => $this->productType()->toArray(),
            'productVariant' => $this->productVariant()->toArray(),
            'productFormat' => $this->productFormat()->toArray(),
            'language' => $this->brand()->language(),
            'issue_nr' => $this->issuenr(),
            'issueDisplayName' => $this->issueDisplayName(),
            'issue_evt' => $this->evt(),
            'displayname' => $this->displayName(),
            //'cover' => $this->cover(),
            'image' => $this->getImage(),
            'params' => $this->getParams()
        );
    }

    /**
     * returns the unique ID.
     *
     * if the ID was set manually before, it will return that ID
     *
     * otherwise it will construct the ID from main and subproducts and concatenate them with ';'
     *
     * @return string
     */
    public function id()
    {
        if (isset($this->id) && $this->id != '') return $this->id;
        $a = array();
        $a[] = $this->mainproduct->id();
        if (count($this->addons)) {
            foreach ($this->addons as $addon) {
                $a[] = $addon->id();
            }
        }
        return implode(';', $a);
    }

    /**
     * returns the sku. It's either the sku of the mainproduct or a custom one
     *
     * @return string
     */
    public function sku()
    {
        if (isset($this->sku) && $this->sku != '') return $this->sku;
        return $this->mainproduct->sku();
    }

    /**
     * returns the brand object of the mainproduct
     *
     * @return \Spotlight\Brand\Entity\Brand
     */
    public function brand()
    {
        return $this->mainproduct->brand();
    }

    /**
     * //TODO implement bundle instead of mainproduct
     *
     * @return stdSpotlight
     */
    public function productType()
    {
        return $this->mainproduct->productType();
    }

    /**
     * //TODO implement bundle instead of mainproduct
     *
     * @return stdSpotlight
     */
    public function productVariant()
    {
        return $this->mainproduct->productVariant();
    }

    /**
     * //TODO implement bundle instead of mainproduct
     *
     * @return stdSpotlight
     */
    public function productFormat()
    {
        return $this->mainproduct->productFormat();
    }

    /**
     * returns the issuenr of the main product
     *
     * //TODO: for some combinations that could be invalid. e.g. imagine a bundle of spotlight and business sptolight magazine...
     *
     * @return string
     */
    public function issuenr()
    {
        return $this->mainproduct->issuenr();
    }

    /**
     * returns the human readable Name of the issuenumber
     *
     * like Ausgabe 04/20015
     *
     * @return string
     */
    public function issueDisplayName()
    {
        if ($this->mainproduct->isIssuebased()) {
            $year = substr($this->issuenr(), 0, 4);
            $is = substr($this->issuenr(), 4, 2);
            return "Ausgabe " . $is . "/" . $year;
        } else {
            return false;
        }

    }

    /**
     * returns the current evt-date of the mainproduct
     *
     * //TODO: for some combinations that could be invalid. e.g. imagine a bundle of spotlight magazine with express...
     *
     * @return int unixtimestamp
     */
    public function evt()
    {
        return $this->mainproduct->evt();
    }

    /**
     * returns the combined display Name
     *
     * @return string
     */
    public function displayName()
    {
        $displayname = array();
        $displayname[] = $this->mainproduct->displayName();
        $concatenator = 'mit';
        $i = 0;
        if (count($this->addons)) {
            foreach ($this->addons as $addon) {
                $displayname[] = $concatenator;
                $displayname[] = $addon->productVariant()->displayName();
                $concatenator = 'und';
            }
        }
        return implode(' ', $displayname);

    }

    /**
     * returns the image as an object
     *
     * @return Image
     */
    public function image()
    {
        $image_params = $this->getParams();

        $mainProduct = $this->mainProduct();
        $mainProductBrand = $mainProduct->brand();
        $mainProductFormat = $mainProduct->productFormat();

        if (empty($this->addons())) {
            return $mainProduct->image();
        }
        else {
            $addons = $this->addons();

            $kombi = array();
            foreach ($addons as $addon_id => $addon) {

                $product_type_obj = $addon->productType();
                $product_type = $product_type_obj->name();

                $product_variant_obj = $addon->productVariant();
                $product_variant = $product_variant_obj->name();
                $kombi[] = array(
                    'type' => $product_type,
                    'variant' => $product_variant
                );
            }

            $image_params['brand_id'] = $mainProductBrand->id();
            $image_params['format'] = $mainProductFormat->name;
            $image_params['kombi'] = $kombi;

            $image = new \Spotlight\File\Entity\Image($image_params);
            return $image;
        }
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

    public function mainProduct()
    {
        return $this->mainproduct;
    }

    public function addons()
    {
        return $this->addons;
    }

    public function setDate($timestamp)
    {
        $this->mainproduct->setDate($timestamp);
    }

}
