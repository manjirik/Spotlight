<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandBusinessSpotlight
   *
   * Business Spotlight implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandBusinessSpotlight extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '96';
      $this->displayname = 'Business Spotlight';
      $this->name = 'business-spotlight';
      $this->claim = 'Englisch für den Beruf';
      $this->teacheraddon = 'in the classroom';
      $this->language = 'englisch';
      $this->frequency = 2;
    }

  }
