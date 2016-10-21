<?php

/**
 * @file
 * Contains \Spotlight\File\Entity\Image.
 */

namespace Spotlight\File\Entity;

use Spotlight\File\ImageBase;
use Spotlight\Map\EntityMap;
/**
 * Provides helpers to operate on images.
 *
 * @ingroup File
 */
class Image extends ImageBase {

 /**
   * Path of the image file.
   *
   * @var string
   */
  protected $source = '';

  /**
   * Device definition of the image file.
   *
   * @var string
   */
  protected $device;

  /**
   * Image variant.
   *
   * @var string
   */
  protected $image_variant;

  /**
   * Image format.
   *
   * @var string
   */
  protected $image_format;

  /**
   * Name Image file.
   *
   * @var string
   */
  protected $name;

  /**
   * Issue NR.
   *
   * @var string
   */
  protected $issue_nr;

  /**
   * Brand cdn definition of the image file (URL).
   *
   * @var string
   */
  protected $brand_url;

  /**
   * Map Array.
   *
   * @var array
   */
  protected $spotlight_map;

  /**
   * An image toolkit object.
   *
   * @var \Drupal\Core\ImageToolkit\ImageToolkitInterface
   */
  protected $toolkit;

  /**
   * An object.
   *
   * @var
   */
  protected $params;

  /**
   * File size in bytes.
   *
   * @var int
   */
  protected $fileSize;

  /**
   * Constructs a new Image object.
   *
   */
  public function __construct(Array $p = array()) {

    $this->params = $p;
    $this->spotlight_map = \Spotlight\Map\EntityMap::mapArray();

    $this->device = $this->setDevice();
    $this->issue_nr = $this->setIssueNr();
    $this->brand_url = $this->setBrandURL();
    $this->name = $this->setName();

    $this->setImageFormat();

    $this->image_variant = $this->setImageVariant();
    $this->kombi_name = $this->setKombiName();

    if (!empty($this->kombi_name)) {
      $this->name = 'm'.$this->kombi_name;
    }
    else {
      $this->name = $this->setName();
    }

  }

  /**
   * {@inheritdoc}
   */
  public function isValid() {
    return $this->getToolkit()->isValid();
  }


  /**
   * {@inheritdoc}
   */
  protected function setKombiName() {
    if (!empty($this->params['kombi'])) {
      $name = FALSE;
      foreach ($this->params['kombi'] as $key => $value) {
        if (!empty( $this->spotlight_map['map_magazin_variant_kombi']) ) {
          if (!empty($value['type']) && array_key_exists($value['type'], $this->spotlight_map['map_magazin_variant_kombi'])) {
            if (!empty($value['variant']) && array_key_exists($value['variant'], $this->spotlight_map['map_magazin_variant_kombi'][$value['type']])) {
              $image_variant = $this->spotlight_map['map_magazin_variant_kombi'][$value['type']][$value['variant']];
              $name .= $image_variant;
            }
          }
        }
      }

      return $name.'.png';
    }
    else {
      return FALSE;
    }
  }
  /**
   * {@inheritdoc}
   */
  protected function setBrandURL() {

    if (!empty($this->params['brand_id']) && array_key_exists($this->params['brand_id'], $this->spotlight_map['map_bid_url'])) {
      return $this->spotlight_map['map_bid_url'][$this->params['brand_id']];
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function setImageFormat() {

    if (!empty($this->params['format'])) {
      if (array_key_exists($this->image_variant, $this->spotlight_map['map_magazin_format_product'])) {
        $this->image_format = $this->spotlight_map['map_magazin_format_product'][$this->image_variant][$this->params['format']];
      }
      else {
        $this->image_format = $this->spotlight_map['map_magazin_format_product']['magazine'][$this->params['format']];
      }
    }
    else {
      return FALSE;
    }

  }
  /**
   * {@inheritdoc}
   */
  protected function getImageFormat() {
    return $this->image_format;
  }
  /**
   * {@inheritdoc}
   */
  protected function setImageVariant() {
    if (!empty($this->params['type']) && !empty($this->params['variant'])) {
      if (!empty($this->spotlight_map) && !empty($this->spotlight_map['map_magazin_variant_product'])) {
        if (array_key_exists($this->params['type'], $this->spotlight_map['map_magazin_variant_product'])) {
          if (array_key_exists($this->params['variant'], $this->spotlight_map['map_magazin_variant_product'][$this->params['type']])) {

            return $this->spotlight_map['map_magazin_variant_product'][$this->params['type']][$this->params['variant']];
          }
        }
      }
    }
    return FALSE;
  }
  /**
   * {@inheritdoc}
   */
  protected function setDevice() {
    if (!empty($this->params['device']) && in_array($this->params['device'], $this->deviceponsive)) {
      return $this->params['device'];
    }
    else {
      return 'desktop';
    }
  }
  /**
   * {@inheritdoc}
   */
  protected function setName() {
    if (!empty($this->params['name'])) {
      return $this->params['name'].'.png';
    }
    else {
      return FALSE;
    }
  }
  /**
   * {@inheritdoc}
   */
  protected function getName() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  protected function setIssueNr() {
    if (!empty($this->params['issue_nr']) && ctype_digit($this->params['issue_nr']) && strlen($this->params['issue_nr']) == 6) {
      return $this->params['issue_nr'];
    }
    else {
      return FALSE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getHeight() {
    return 'height';
  }

  /**
   * {@inheritdoc}
   */
  public function getWidth() {
    return 'width';
  }

  /**
   * {@inheritdoc}
   */
  public function getFileSize() {
    return 'fileSize';
  }

  /**
   * {@inheritdoc}
   */
  public function getMimeType() {
    return 'MimeType';
  }

  /**
   * Provides a dynamic image URL.
   *
   * @return URL (string)
   */
  public function url() {

    // $protocol = 'https';
    $url = $this->cdn;

    if (empty($this->brand_url)) {
      return FALSE;
    }
    $url .= '/'.$this->brand_url;

    if (!empty($this->device) && $this->device !== 'desktop' ) {
      $url .= '/'.$this->device;
    }
    if (!empty($this->issue_nr) && empty($this->image_variant)) {
      $url .= '/'.$this->issue_nr;
    }

    if (!empty($this->image_variant)) {
      $url .= '/'.$this->image_variant;
    }
    if (!empty($this->kombi_name)) {
      $url .= '/kombi';
    }

    if (!empty($this->image_format)) {
      $url .= '/'.$this->image_format;
    }

    if (empty($this->name)) {
      return FALSE;
    }
    $url .= '/'.$this->name;


    // $file_headers = @get_headers($protocol.':'.$url);
    $file_headers = @get_headers($url);
    if (empty($file_headers) or !stripos($file_headers[0], '200 OK')) {
      return FALSE;
    }
    return $url;
  }
}
