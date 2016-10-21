<?php
    
namespace Spotlight\Brand;
  use Spotlight\EntityBase;
  use JMS\Serializer\Annotation as Jms;

  /**
   * Base Class for all Brand specific implementations
   *
   * implements all methods that are shared among all Brand subclass implementations
   *
   * @package Spotlight\Brand
   */
  abstract class BrandBase extends EntityBase {

    /**
     * container for a specific Brand subclass
     *
     *
     * @var \Spotlight\Brand\Entity\Brand (or one of the special Versions like BrandAdesso, ...)
     */
    protected $brand;

    /**
     * the claim (subtitle) of the products
     *
     * @Jms\Type("string")
     *
     * @var string
     */
    protected $claim;

    /**
     * the language of the brand used within our products.
     *
     * @Jms\Type("string")
     *
     * @var string
     */
    protected $language;

    /**
     * the name of the teacher add on product (Lehrerbeilage)
     *
     * @Jms\Type("string")
     *
     * @var string
     */
    protected $teacheraddon;

    /**
     * frequency implementation on brand level
     *
     * @Jms\Type("integer")
     *
     * @var int
     */
    protected $frequency;

    /**
     * returns all relevant information as keyed array (serialisation)
     *
     * @return array
     */
    public function toArray()
    {
      return array(
          'id' => $this->id(),
          'displayname' => $this->displayname(),
          'name' => $this->name(),
          'claim' => $this->claim(),
          'language' => $this->language(),
          'teacheraddon' => $this->teacheraddon(),
          'frequency' => $this->frequency(),
      );
    }

    /**
     * returns the claim (subtitle) of the products
     *
     * eg. 'Die schönsten Seiten auf Italienisch'
     *
     * defined per brand
     *
     * @return string
     */
    public function claim() {
      return $this->claim;
    }

    /**
     * returns the language of the brand used within our products.
     *
     * defined per brand
     *
     * MUST be one of englisch, französisch, spanisch, italienisch, deutsch
     *
     * @return string
     */
    public function language() {
      return $this->language;
    }

    /**
     * returns the name of the teacher add on product (Lehrerbeilage)
     *
     * it's WITHOUT the brandname ( as this can be easily contructed)
     *
     * e.g. in classe --> ADESSO in classe
     *
     * defined per brand
     *
     * @return string
     */
    public function teacheraddon() {
      return $this->teacheraddon;
    }

    /**
     * at which frequency are the products belonging to the brand published?
     *
     * the frequency is used to calculate the number of issues within a given timeframe (in relation to 1 month)
     *
     * e.g. business spotlight
     *
     * --> frequency = 0.5 ( / month)
     *
     * --> published every two month
     *
     * defined per brand
     *
     * frequency can ALSO be defined on the product level. if defined there, IT WILL OVERRIDE THE BRAND frequency
     *
     * e.g. spotlight express
     *
     * --> frequency = 2 ( / month)
     *
     * --> published twice a month
     *
     * overrides frequency for spotlight brand (which is 1)
     *
     * @return integer
     */
    public function frequency() {
      return $this->frequency;
    }

  }
