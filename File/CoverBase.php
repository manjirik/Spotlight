<?php

namespace Spotlight\File;
  use Spotlight\EntityBase;

  abstract class CoverBase extends EntityBase {

    protected $filename;
    protected $url;
    protected $filemime;
    protected $filesize;
    protected $status;

    public function status() {
      return $this->status;
    }

    public function url() {
      return $this->url;
    }
    public function filename() {
      return $this->filename;
    }

    public function toArray() {
      return array(
        'filename' => $this->filename(),
      );
    }

  }
