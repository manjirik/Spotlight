<?php

namespace Spotlight\File;
  use Spotlight\EntityBase;

  abstract class ImageBase extends EntityBase {

    protected $filename;
    protected $url;
    protected $filemime;
    protected $filesize;
    protected $status;

    // protected $cdn = '//cdn.spotlight-verlag.de';
    protected $cdn = 'https://d2rwcenfr2y6nr.cloudfront.net';
    protected $deviceponsive = array('phone', 'tablet', 'desktop');

    public function url() {
      return $this->url;
    }

    public function toArray() {
      return array(
        'url' => $this->url(),
      );
    }

  }
