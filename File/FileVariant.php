<?php

    namespace Spotlight\File;
    use Spotlight\File\FileVariantBase;

    class FileVariant extends FileVariantBase {

      public function __construct($name = 'magazin') {
        $this->name = (string) $name;
        $this->displayname =  (string) $name;
        $this->name = strtolower($this->name);

        switch($this->name) {
          case 'magazin':
            $this->id = '00';
            $this->name = 'magazin';
            break;

          case 'teacher':
          case 'lehrerbeilage':
            $this->id = '01';
            $this->name = 'lehrerbeilage';
            break;

          case 'plus':
          case 'uebungsheft-plus':
            $this->id = '02';
            $this->name = 'uebungsheft-plus';
            break;

          // case 'express':
          //   $this->id = '03';
          //   $this->name = 'express';
          //   break;

          case 'audio':
          case 'audio-trainer':
            $this->id = '04';
            $this->name = 'audio-trainer';
            break;

          case 'green_light':
          case 'heftbeilage':
            $this->id = '05';
            $this->name = 'heftbeilage';
            break;

          case 'kombi':
            $this->id = '06';
            $this->name = 'kombi';
            break;
        }
      }

      public function toArray() {
        return array(
          'id' => $this->id(),
          'name' => $this->name(),
        );
      }
    }
