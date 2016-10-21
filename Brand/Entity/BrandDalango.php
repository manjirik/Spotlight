<?php
    
namespace Spotlight\Brand\Entity;
  use Spotlight\Brand\BrandBase;

  /**
   * Class BrandDalango
   *
   * dalango implementation of {@link \Spotlight\Brand\Entity\Brand}
   *
   * @package Spotlight\Brand\Entity
   */
  class BrandDalango extends BrandBase {

    /**
     * constructed by dalango language identifier
     *
     * @param string $identifier
     */
    public function __construct($identifier)
    {
      $this->id = (string)$identifier;
      $this->language = $this->setLanguage($identifier);
      $this->displayname = 'dalango' . ' ' . $this->language;
      $this->name = 'dalango' . '_' . $this->language;
    }

    /**
     * set the dalango language id
     *
     * @param string $identifier
     * @return string
     */
    private function setLanguage($identifier)
    {
      switch ($identifier) {
        case '71':
          return 'englisch';
        case '72':
          return 'französisch';
        case '73':
          return 'spanisch';
        case '74':
          return 'italienisch';
        case '76':
          return 'englisch';
        case '79':
          return 'express';
      }
      return 'undefined';
    }

  }
