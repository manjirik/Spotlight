<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandSpotlight
   *
   * Spotlight implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandSpotlight extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '91';
      $this->displayname = 'Spotlight';
      $this->name = 'spotlight';
      $this->claim = 'Einfach Englisch!';
      $this->teacheraddon = 'in the classroom';
      $this->language = 'englisch';
      $this->frequency = 1;
    }

  }
