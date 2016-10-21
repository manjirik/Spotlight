<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandAdesso
   *
   * ADESSO implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandAdesso extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '94';
      $this->displayname = 'ADESSO';
      $this->name = 'adesso';
      $this->claim = 'Die schönsten Seiten auf Italienisch';
      $this->teacheraddon = 'in classe';
      $this->language = 'italienisch';
      $this->frequency = 1;
    }

  }
