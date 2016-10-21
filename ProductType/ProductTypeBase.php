<?php

  namespace Spotlight\ProductType;
    use Spotlight\Entitybase;

    /**
     * Class ProductTypeBase
     *
     * add frequency as as mandatory parameter to entity base class
     *
     * @package Spotlight\ProductType
     */
    abstract class ProductTypeBase extends Entitybase {

        /**
         * frequency implementation on product level
         *
         * @var int
         * @see
         */
        protected $frequency;
        /**
         * is the product type issuebased or not (-->like dalango, express)
         *
         * @var bool
         */
        protected $issuebased;

        /**
         * returns the frequency information on product level
         *
         * this will always overwrite the settings from brand level
         *
         * @return int frequency
         */
        public function frequency() {
          return $this->frequency;
        }

        /**
         * is the product type issuebased or not (-->like dalango, express)
         *
         * @return bool
         */
        public function isIssuebased()
        {
            return $this->issuebased;
        }
    }