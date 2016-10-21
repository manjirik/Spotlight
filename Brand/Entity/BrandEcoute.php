<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandEcoute
   *
   * Écoute implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandEcoute extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '92';
      $this->displayname = 'Écoute';
      $this->name = 'ecoute';
      $this->claim = 'Typisch französisch';
      $this->teacheraddon = 'en classe';
      $this->language = 'französisch';
      $this->frequency = 1;
    }

  }
