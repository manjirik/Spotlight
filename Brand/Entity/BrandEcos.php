<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandEcos
   *
   * ECOS implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandEcos extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '93';
      $this->displayname = 'ECOS';
      $this->name = 'ecos';
      $this->claim = 'Die Welt auf Spanisch';
      $this->teacheraddon = 'en la clase';
      $this->language = 'spanisch';
      $this->frequency = 1;
    }

  }
