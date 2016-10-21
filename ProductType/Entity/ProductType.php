<?php

  namespace Spotlight\ProductType\Entity;
    use Spotlight\ProductType\ProductTypeBase;

    /**
     * Class ProductType
     * @package Spotlight\ProductType\Entity
     */
    class ProductType extends ProductTypeBase {

      /**
       * @param string $identifier
       * $identifier can be used in two formats:
       * either ID-like: '01' OR name-like : 'printproduct'
       */
      public function __construct($identifier = 'printproduct')
      {

        if (intval($identifier) == 0) { // if we pass in the id like '01' assign it to $this->id
          $this->id = $identifier;
        } else { // if we pass in the id like 'printproduct' assign it to $this->name
          $this->name = (string)$identifier;
        }

        switch ($identifier) {
          case 'undefined':
          case '00':
            $this->id = '00';
            $this->name = 'undefined';
            $this->displayname = 'Undefiniert';
            break;
          case 'printproduct':
          case '01':
            $this->id = '01';
            $this->name = 'printproduct';
            $this->displayname = 'Printprodukt';
            break;
          case 'audioproduct':
          case '02':
            $this->id = '02';
            $this->name = 'audioproduct';
            $this->displayname = 'Audioprodukt';
            break;
          case 'onlineproduct':
          case '03':
            $this->id = '03';
            $this->name = 'onlineproduct';
            $this->displayname = 'Online-Zugang';
            break;
        }

      }

      /**
       * @return array
       */
      public function toArray() {
        return array(
          'id' => $this->id(),
          'displayname' => $this->displayname(),
          'name' => $this->name(),
        );
      }

    }