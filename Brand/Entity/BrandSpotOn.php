<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandSpotOn
   *
   * SpotOn implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandSpotOn extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '95';
      $this->displayname = 'Spot On';
      $this->name = 'spoton';
      $this->claim = '';
      $this->teacheraddon = '';
      $this->language = 'englisch';
      $this->frequency = 1;
    }

  }
