<?php

namespace Spotlight\Product\Entity;
  use Spotlight\Brand\Entity\Brand;
  use Spotlight\EntityBase;
  use Spotlight\Evt\Service\EvtService;
  use Spotlight\Product\ProductInterface;
  use Spotlight\ProductType\Entity\ProductFormat;
  use Spotlight\ProductType\Entity\ProductType;
  use Spotlight\ProductType\Entity\ProductVariant;
  use Spotlight\File\Entity\Image;

  /**
   * Central class for creating all kind of products.
   *
   * the products are always 'stand alone' items (e.g. a single magazine, or a single plus-Heft,...)
   * They are used to get a representation of that item (e.g. in download archiv)
   *
   * CAUTION: The products are NOT subscribable, thus they cannot be added to a subscription.
   * If you need a Product for a subscription, you HAVE TO USE one of the Bundles instead (even for single products like magazines...)
   *
   * {@see \Spotlight\Product\Bundles\Magazine} as an example
   *
   * A product is always constructed out of 4 parameters.
   *
   *
   * * Brand: {@see \Spotlight\Brand\Entity\Brand}
   *
   * * ProductType: {@see \Spotlight\ProductType\Entity\ProductType}
   *
   * * ProductVariant: {@see \Spotlight\ProductType\Entity\ProductVariant}
   *
   * * ProductFormat: {@see \Spotlight\ProductType\Entity\ProductFormat}
   *
   * @package Spotlight\Product\Entity
   */
  class Product extends EntityBase implements ProductInterface
  {

    /**
     * as400 product identifier
     *
     * spotlight SKU, (like 91100)
     *
     * @var string
     */
    protected $sku;

    /**
     * container for a specific Brand subclass
     *
     * @var Brand (or one of the special Versions like BrandAdesso, ...)
     */
    protected $brand;

    /**
     * defines the base type of the product
     *
     * (online-access, printbased, audiobased)
     *
     * @var \Spotlight\ProductType\Entity\ProductType
     */
    protected $product_type;

    /**
     * Defines the variant of the product (variation of the base type)
     *
     * could be addon(s) (like plus, teacher) or special cases like dalango and express
     *
     * @var \Spotlight\ProductType\Entity\ProductVariant
     */
    protected $product_variant;

    /**
     * the format defines the delivery / access method for the given product
     *
     * like physical == magazin / CD
     *
     * or download == epaper / audio-download
     *
     * or online-access (for dalango and possibly digital XL)
     *
     * @var \Spotlight\ProductType\Entity\ProductFormat
     */
    protected $product_format;

    /**
     * The string representation of an issue.
     *
     * issuenumber in Format YYYYXX
     *
     * @var string
     */
    protected $issuenr;

    /**
     * container for the EVT service
     *
     * @var EvtService
     */
    protected $evtservice;

    /**
     * unixtimestamp
     *
     * @var int
     */
    protected $evt;

    /**
     * frequency on product level
     *
     * @var int
     */
    protected $frequency;

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
     * The construction parameters can be passed in in two different formats:
     *
     * * by 'name' e.g 'spotlight'
     *
     * * by internal id '01', '02', ...
     *
     * The second option is mainly used internally for (re-creating) products by product ID
     * {@see \Spotlight\Product\Entity\ProductById}
     *
     * @param string $brandid
     * @param string $type
     * @param string $variant
     * @param string $format
     */
    public function __construct(
      $brandid,
      $type,
      $variant,
      $format,
      $params = array()
    )
    {
      $this->brand = new Brand($brandid);
      $_brandid = $this->brand->id();

      if (substr($_brandid, 0, 1) == '9') {
        if (($type == 'onlineproduct' || $type == '03')) {
          $variant = 'premium';
          $format = 'access';
        }
        if ($format == 'access' || $format == '03') {
          $variant = 'premium';
          $type = 'onlineproduct';
        }
      }
      if (substr($_brandid, 0, 1) == '7') {
        $type = 'onlineproduct';
        $variant = 'dalango';
        $format = 'access';
      }

      $this->product_type = new ProductType($type);
      $this->product_variant = new ProductVariant($variant);
      $this->product_format = new ProductFormat($format);

      if ($this->isIssuebased()) {
        $this->evtservice = new EvtService();
        $now = time();
        $this->setDate($now);

        $this->frequency = $this->product_variant->frequency() ? $this->product_variant->frequency() : $this->brand->frequency();
      }

      $this->setSku();
      $this->setParams($params);
    }

    /**
     * is the product type issuebased or not (-->like dalango, express)
     *
     * @return bool
     */
    public function isIssuebased()
    {
      return $this->productVariant()->isIssuebased();
    }

    /**
     * returns the product variant object
     *
     * @return ProductVariant
     */
    public function productVariant()
    {
      return $this->product_variant;
    }

    /**
     * sets  $this->issuenr and $this->evt by $time
     *
     * it sets both properties like this:
     *
     * The passed in $time can have any value. The evtservice finds the current / actual issue
     * (the issue that is already published at the LAST EVT before $time)
     *
     * $this->evt is set to the EVT-Date of that CALCULATED issue
     *
     * @param int $time unixtimestamp
     */
    public function setDate($time)
    {

      $this->issuenr = $this->evtservice->getIssueByDate(
          $this->brand,
          $this->product_format,
          $this->product_variant,
          $time
      );

      $this->evt = $this->evtservice->getEvtDateByIssue(
          $this->brand,
          $this->product_format,
          $this->product_variant,
          $this->issuenr
      );
    }

    /**
     * set the SKU based on the given properties
     *
     * helper function: called on __contruct() if needed.
     *
     */
    private function setSku()
    {
      $this->sku = $this->brand->id();
      switch ($this->product_type->id()) {
        case '01': // printproduct
          switch ($this->product_format->id()) {
            case '01': // Physikalisch
              $this->sku .= '100';
              break;
            case '02': // Download
              $this->sku .= '600';
              break;
          }
          break;
        case '02': // audioproduct
          switch ($this->product_format->id()) {
            case '01': // Physikalisch
              $this->sku .= '300';
              break;
            case '02': // Download
              if ($this->product_variant->name() == 'express') {
                $this->sku .= '800';
              } else {
                $this->sku .= '700';
              }
              break;
          }
          break;
        case '03': // onlineproduct
          switch ($this->product_variant->id()) {
            case '06': // Premium
              $this->sku .= '500';
              break;
            case '07': // dalango
              $this->sku .= '100'; // laufzeit not respected default: 12 month
              break;
          }
      }
    }

    /**
     * returns the machine readable name
     *
     * @return string
     */
    public function name()
    {
      $a = array(
          $this->brand->name(),
          $this->product_type->name(),
          $this->product_variant->name(),
          $this->product_format->name(),
          $this->issuenr(),
      );
      return implode('-', $a);
    }

    /**
     * returns the string representation of an issue.
     *
     * issuenumber in Format YYYYXX
     *
     * @return string
     */
    public function issuenr()
    {
      if ($this->isIssuebased()) {
        return $this->issuenr;
      } else {
        return false;
      }
    }

    /**
     * sets the brand of the current product
     *
     * @param Brand $brand
     */
    public function setBrand(Brand $brand)
    {
      $this->brand = $brand;
    }

    /**
     * returns the product format object
     *
     * @return ProductFormat
     */
    public function productFormat()
    {
      return $this->product_format;
    }

    /**
     * sets  $this->issuenr and $this->evt by $issuenr
     *
     * it sets both properties like this:
     *
     * The passed in $issuenr can have any (valid) value.
     *
     * evtservice calculates the corresponding EVT-Date
     *
     * $this->evt is set to the EVT-Date of that CALCULATED issue
     *
     * @param $issuenr
     */
    public function setIssueNr($issuenr)
    {
      $this->issuenr = $issuenr;
      $this->evt = $this->evtservice->getEvtDateByIssue(
          $this->brand,
          $this->product_format,
          $this->product_variant,
          $this->issuenr()
      );
    }

    /**
     * can this kind of product object be used for a subscription?
     *
     * @return bool
     */
    public function isSubscribable()
    {
      return false;
    }

    /**
     * returns all relevant information as keyed array (serialisation)
     *
     * @return array
     */
    public function toArray()
    {
      return array(
          'id' => $this->id(),
          'sku' => $this->sku(),
          'brand' => $this->brand->toArray(),
          'productType' => $this->product_type->toArray(),
          'productVariant' => $this->product_variant->toArray(),
          'productFormat' => $this->product_format->toArray(),
          'language' => $this->brand->language(),
          'issue_nr' => $this->issuenr(),
          'issueDisplayName' => $this->issueDisplayName(),
          'issue_evt' => $this->evt(),
          'issue_month' => $this->month(),
          'issue_month_de_string' => $this->monthDeString(),
          'issue_year' => $this->year(),
          'displayname' => $this->displayName(),
          'cover' => $this->cover(),
      );
    }

    /**
     * returns a unique ID for a certain product
     *
     * it is constructed out of the IDs of each single parameter, describing that product...
     *
     * That way it is possible to fully (re-)create a product by it's unique ID.
     *
     * @return string
     */
    public function id()
    {
      $a = array(
          $this->brand->id(),
          $this->product_type->id(),
          $this->product_variant->id(),
          $this->product_format->id(),
          $this->issuenr(),
      );
      return implode('-', $a);
    }

    /**
     * returns the spotlight SKU (like 91100)
     *
     * @return string
     */
    public function sku()
    {
      return $this->sku;
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
      if ($this->isIssuebased()) {
        $year = substr($this->issuenr, 0, 4);
        $is = substr($this->issuenr, 4, 2);
        return "Ausgabe " . $is . "/" . $year;
      } else {
        return false;
      }
    }

    /**
     * returns the calculated EVT-Date as a unix timestamp
     *
     * @return int
     */
    public function evt()
    {
      if ($this->isIssuebased()) {
        return $this->evt;
      } else {
        return false;
      }
    }

    /**
     * returns the month (numerical) of the calculated issue
     *
     * @return string
     */
    public function month()
    {
      if ($this->isIssuebased()) {
        $is = (int)substr($this->issuenr, 4, 2);
        if ($this->frequency() == 1) return (string)$is;
        if ($this->frequency() == 2) return (string)(2 * $is - 1);
        if ($this->frequency() == 0.5) return (string)floor(($is + 1) / 2);
        return (string)$is;
      } else {
        return false;
      }
    }

    /**
     * returns the month (string DE) of the calculated issue
     *
     * @return string
     */
    public function monthDeString()
    {

      $monthName = [
        1 => "Januar",
        2 => "Februar",
        3 => "März",
        4 => "April",
        5 => "Mai",
        6 => "Juni",
        7 => "Juli",
        8 => "August",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "Dezember"
      ];

      if ($this->isIssuebased()) {
        $is = (int)substr($this->issuenr, 4, 2);
        if ($this->frequency() == 1) {
          $m = (string)$is;
          return array_key_exists($m, $monthName) ? $monthName[$m] : FALSE;
        }
        if ($this->frequency() == 2) {
          $m = (string)(2 * $is - 1);
          return array_key_exists($m, $monthName) ? $monthName[$m] : FALSE;
        }
        if ($this->frequency() == 0.5) {
          $m = (string)floor(($is + 1) / 2);
          return array_key_exists($m, $monthName) ? $monthName[$m] : FALSE;
        }
        $m = (string)$is;
        return array_key_exists($m, $monthName) ? $monthName[$m] : FALSE;
      } else {
        return false;
      }
    }

    /**
     * returns the frequency of the product
     *
     * @return int
     */
    public function frequency()
    {
      if ($this->isIssuebased()) {
        return $this->frequency;
      } else {
        return false;
      }
    }

    /**
     * returns the year of the calculated issue
     *
     * @return string
     */
    public function year()
    {
      if ($this->isIssuebased()) {
        return $year = substr($this->issuenr, 0, 4);
      } else {
        return false;
      }
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function displayName()
    {

      $brand = $this->brand()->displayName();
      $type = '';

      switch ($this->productType()->name()) {
        case 'printproduct':
          switch ($this->productVariant()->name()) {
            case 'standard':
              $type = ' Magazin';
              break;
            case 'teacher':
              $type = ' ' . $this->brand()->teacheraddon();
              break;
            case 'plus':
              $type = ' Magazin <i>plus</i>';
              break;
          }
          break;
        case 'audioproduct':
          switch ($this->productVariant()->name()) {
            case 'standard':
              $type = ' Audio-Trainer';
              break;
            case 'express':
              $type = ' express';
              break;
          }
        case 'onlineproduct':
          switch ($this->productVariant()->name()) {
            case 'premium':
              $type = ' Premium';
              break;
          }
      }
      return $brand . $type;
    }

    /**
     * returns the brand object
     *
     * @return Brand
     */
    public function brand()
    {
      return $this->brand;
    }

    /**
     * returns the product type object
     *
     * @return ProductType
     */
    public function productType()
    {
      return $this->product_type;
    }

    /**
     * returns the image as an object
     *
     * @return Image
     */
    public function image()
    {
        $image_params = $this->getParams();
        $brand = $this->brand();

        $productType = $this->productType();
        $productVariant = $this->productVariant();
        $productFormat = $this->productFormat();


        $image_params['brand_id'] = $brand->id;
        $image_params['name'] = $this->issuenr();
        $image_params['type'] = $productType->name;
        $image_params['variant'] = $productVariant->name;
        $image_params['format'] = $productFormat->name;

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
    /**
     * returns EVT service
     *
     * @return object
     */
    public function getEVTService()
    {
      return $this->evtservice;
    }

    /**
     * Returns product url
     *
     * This method returns url to the product based on 2 mandatory params
     *
     * @param $view
     *
     * @param $type
     *
     * @return string
     */
    public function Url($view, $format = NULL)
    {
      if(empty($view)) return false;

      $brand = $this->brand()->name();
      $productType = $this->ProductType()->name();
      $productVariant = $this->ProductVariant()->name();
      $issueNumber = $this->issuenr();

      $url = "https://media.spotlight-verlag.de/issue/".$brand."/".$productType."/".$productVariant."/".$issueNumber."/".$view."/".$format;

      return $url;
    }
  }
