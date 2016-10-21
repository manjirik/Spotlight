<?php

  namespace Spotlight\ProductType\Entity;
    use Spotlight\ProductType\ProductTypeBase;

    /**
     * Class ProductFormat
     * @package Spotlight\ProductType\Entity
     */
    class ProductFormat extends ProductTypeBase {

      /**
       * @param string $identifier
       * $identifier can be used in two formats:
       * either ID-like: '01' OR name-like : 'physical'
       */
      public function __construct($identifier = 'undefined')
      {

        if (intval($identifier) == 0) { // if we pass in the id like '01' assign it to $this->id
          $this->id = $identifier;
        } else { // if we pass in the id like 'printproduct' assign it to $this->name
          $this->name = (string)$identifier;
        }

        switch ($identifier) {
          case 'undefined':
          case '00';
            $this->id = '00';
            $this->name = 'undefined';
            $this->displayname = 'Undefiniert';
            break;
          case 'physical':
          case '01';
            $this->id = '01';
            $this->name = 'physical';
            $this->displayname = 'Physikalische Lieferung';
            break;
          case 'download':
          case '02';
            $this->id = '02';
            $this->name = 'download';
            $this->displayname = 'Download';
            break;
          case 'access':
          case '03';
            $this->id = '03';
            $this->name = 'access';
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