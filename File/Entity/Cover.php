<?php

  namespace Spotlight\File\Entity;
  use Spotlight\File\CoverBase;
  use Spotlight\File\FileVariant;


  class Cover extends CoverBase {

    protected $product;

    protected $bucket;
    protected $format;
    protected $filename;
    protected $filemime;
    protected $aws_region;
    protected $variant;

    protected $brand;



    public function __construct($params = array()) {
      $this->product = (!empty($params['product']) && is_object($params['product']) ) ? $params['product'] : FALSE;

      if (!empty($this->product)) {
        $product_infos = $this->product->toArray();
        $product_variant = new FileVariant($product_infos['productVariant']['name']);

        $product_brand = $product_infos['brand']['name'];
        $product_issue = $product_infos['issue_nr'];
      }

      $this->bucket = (!empty($params['bucket']) ) ? $params['bucket'] : FALSE;
      $this->aws_region = (!empty($params['aws_region'])  ) ? $params['aws_region'] : FALSE;

      $this->variant = (!empty($params['variant']) ) ? $params['variant'] : $product_variant->name();
      $this->format = (!empty($params['format']) ) ? $params['format'] : 'print';
      $this->filename = (!empty($params['filename']) ) ? $params['filename'] : $product_issue.'.jpg';
      $this->brand = (!empty($params['brand']) ) ? $params['brand'] : $product_brand;

      if (!empty($this->product) && $this->bucket && $this->aws_region) {
        $this->getUrlFromProduct();
      }

    }

    function getUrlFromProduct() {
      if (empty($this->aws_region) || empty($this->bucket) || empty($this->variant) || empty($this->format)) {
        return;
      }

      $url = 'https://s3.'.$this->aws_region.'.amazonaws.com/'.$this->bucket.'/'.$this->brand.'/'.$this->variant.'/'.$this->format.'/'.$this->filename;

      //TODO ...some checks
      $file_headers = @get_headers($url);
      if (empty($file_headers) or !stripos($file_headers[0], '200 OK')) {
        return;
      }
      return $url;
    }

  }
