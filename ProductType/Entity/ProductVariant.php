<?php

  namespace Spotlight\ProductType\Entity;
    use Spotlight\ProductType\ProductTypeBase;

    /**
     * Class ProductVariant
     * @package Spotlight\ProductType\Entity
     */
    class ProductVariant extends ProductTypeBase {

      /**
       * @param string $identifier
       * $identifier can be used in two formats:
       * either ID-like: '00' OR name-like : 'standard'
       */
      public function __construct($identifier = 'standard')
      {
        $this->frequency = false;
        $this->issuebased = true;

        if (intval($identifier) == 0) { // if we pass in the id like '01' assign it to $this->id
          $this->id = $identifier;
        } else { // if we pass in the id like 'printproduct' assign it to $this->name
          $this->name = (string)$identifier;
        }

        switch ($identifier) {
          case 'standard':
          case '00':
            $this->id = '00';
            $this->name = 'standard';
            $this->displayname = 'Standard';
            break;
          case 'plus':
          case '01':
            $this->id = '01';
            $this->name = 'plus';
            $this->displayname = 'plus-Heft';
            break;
          case 'teacher':
          case '02':
            $this->id = '02';
            $this->name = 'teacher';
            $this->displayname = 'Lehrerbeilage';
            break;
          case 'express':
          case '03':
            $this->id = '03';
            $this->name = 'express';
            $this->displayname = 'express';
            $this->frequency = 0.5;
            break;
          case 'addon':
          case '04':
            $this->id = '04';
            $this->name = 'addon';
            $this->displayname = 'Beihefter';
            break;
          case 'special':
          case '05':
            $this->id = '05';
            $this->name = 'special';
            $this->displayname = 'Übersteher / Booklet / Spezial';
            break;
          case 'premium':
          case '06':
            $this->id = '06';
            $this->name = 'premium';
            $this->displayname = 'Premium';
          $this->issuebased = false;
          $this->frequency = false;
            break;
          case 'dalango':
          case '07':
            $this->id = '07';
            $this->name = 'dalango';
            $this->displayname = 'Dalango';
          $this->frequency = false;
          $this->issuebased = false;
            break;
          case 'international':
          case '08':
            $this->id = '08';
            $this->name = 'international';
            $this->displayname = 'International';
            $this->frequency = false;
            $this->issuebased = false;
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
          'frequency' => $this->frequency(),
            'issuebased' => $this->isIssuebased(),
        );
      }

    }