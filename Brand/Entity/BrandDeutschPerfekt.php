<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandDeutschPerfekt
   *
   * deutsch perfekt implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandDeutschPerfekt extends BrandBase {

    /**
     *
     */
    public function __construct() {
      $this->id = '97';
      $this->displayname = 'Deutsch perfekt';
      $this->name = 'deutsch-perfekt';
      $this->claim = 'Einfach Deutsch lernen';
      $this->teacheraddon = 'im Unterricht';
      $this->language = 'deutsch';
      $this->frequency = 1;
    }

  }
