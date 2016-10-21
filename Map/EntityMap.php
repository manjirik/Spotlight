<?php

  namespace Spotlight\Map;

  class EntityMap {

    public function __construct() {
    }

    static public function mapArray() {

      $aws_structure = array(
        'audio-trainer' => array(
          'cd', 'download', 'original'
        ),
        'heftbeilage' => array(
          'epaper', 'original', 'print'
        ),
        'kombi' => array(
          'epaper' => array(
            'up', 'mb'
          ),
          'original', 'print'
        ),
        'lehrerbeilage' => array(
          'epaper', 'original', 'print'
        ),
        'magazin' => array(
          'epaper', 'original', 'print'
        ),
        'uebungsheft-plus' => array(
          'epaper', 'original', 'print'
        )
      );

      $map_aws_variant_product = array(
        'magazin' => array(
          'printproduct', 'standard'
        ),
        'uebungsheft-plus' => array(
          'printproduct', 'plus'
        ),
        'lehrerbeilage' => array(
          'printproduct', 'teacher'
        ),
        'audio-trainer' => array(
          'audioproduct', 'standard'
        ),
        'heftbeilage' => array(
          'printproduct', 'addon'
        )
      );

      $map_magazin_variant_product['printproduct']['standard'] = 'magazin';
      $map_magazin_variant_product['printproduct']['international'] = 'international';
      $map_magazin_variant_product['printproduct']['plus'] = 'uebungsheft-plus';
      $map_magazin_variant_product['printproduct']['teacher'] = 'lehrerbeilage';
      $map_magazin_variant_product['audioproduct']['standard'] = 'audio-trainer';
      $map_magazin_variant_product['audioproduct']['express'] = 'audio-trainer-express';
      $map_magazin_variant_product['printproduct']['addon'] = 'heftbeilage';
      $map_magazin_variant_product['printproduct']['special'] = 'extras';

      $map_magazin_variant_kombi['printproduct']['standard'] = 'm';
      $map_magazin_variant_kombi['printproduct']['plus'] = 'up';
      $map_magazin_variant_kombi['printproduct']['teacher'] = 'l';
      $map_magazin_variant_kombi['audioproduct']['standard'] = 'at';
      $map_magazin_variant_kombi['printproduct']['addon'] = 'h';


      $map_aws_format_product = array(
        'print' => 'physical',
        'epaper' => 'download',
        'cd' => 'physical',
        'download' => 'download',
      );

      $map_magazin_format_product = array(
        // all magazins except audio
        'magazine' => array(
          'physical' => 'print',
          'download' => 'epaper',
        ),
        'audio-trainer' => array(
          'physical' => 'cd',
          'download' => 'download',
        )
      );

      $map_aws_type_product = array(
        'print' => 'printproduct',
        'epaper' => 'printproduct',
        'cd' => 'audioproduct',
        'download' => 'onlineproduct',
      );

      $map_bid_url = array(
        91 => 'spotlight',
        92 => 'ecoute',
        93 => 'ecos-online',
        94 => 'adesso-online',
        95 => 'spoton',
        96 => 'business-spotlight',
        97 => 'deutsch-perfekt',
        71 => 'dalango',
        72 => 'dalango',
        73 => 'dalango',
        74 => 'dalango',
        76 => 'dalango',
        79 => 'dalango',
      );

      $censhare_brand_map = array(
        'Adesso' => array(
          'bid' => 94,
          'brand_name' => 'adesso-online'
        ),
        'BS' => array(
          'bid' => 96,
          'brand_name' => 'business-spotlight'
        ),
        'DP' => array(
          'bid' => 97,
          'brand_name' => 'deutsch-perfekt'
        ),
        'Ecos' => array(
          'bid' => 93,
          'brand_name' => 'ecos-online'
        ),
        'Ecoute' => array(
          'bid' => 92,
          'brand_name' => 'ecoute'
        ),
        'SP' => array(
          'bid' => 91,
          'brand_name' => 'spotlight'
        )
      );

      $brand_map = array(
        'adesso-online' => array(
          'bid' => 94,
          'brand_name' => 'adesso-online',
          'product_name' => 'adesso'
        ),
        'business-spotlight' => array(
          'bid' => 96,
          'brand_name' => 'business-spotlight',
          'product_name' => 'business-spotlight'
        ),
        'deutsch-perfekt' => array(
          'bid' => 97,
          'brand_name' => 'deutsch-perfekt',
          'product_name' => 'deutsch-perfekt'
        ),
        'ecos-online' => array(
          'bid' => 93,
          'brand_name' => 'ecos-online',
          'product_name' => 'ecos'
        ),
        'ecoute' => array(
          'bid' => 92,
          'brand_name' => 'ecoute',
          'product_name' => 'ecoute'
        ),
        'spotlight' => array(
          'bid' => 91,
          'brand_name' => 'spotlight',
          'product_name' => 'spotlight'
        )
      );

      $map_censhare_variant_product = array(
        'Magazin' => array(
          'printproduct', 'standard'
        ),
        'Plus' => array(
          'printproduct', 'plus'
        ),
        'Lehrerbeilage' => array(
          'printproduct', 'teacher'
        ),
        'Audio_Booklet' => array(
          'audioproduct', 'standard'
        ),
        'Supplement' => array(
          'printproduct', 'addon'
        )
      );

      return array(
        'aws_structure' => $aws_structure,
        'map_aws_variant_product' => $map_aws_variant_product,
        'map_aws_format_product' => $map_aws_format_product,
        'map_aws_type_product' => $map_aws_type_product,
        'map_bid_url' => $map_bid_url,
        'map_magazin_format_product' => $map_magazin_format_product,
        'map_magazin_variant_product' => $map_magazin_variant_product,
        'map_magazin_variant_kombi' => $map_magazin_variant_kombi,
        'censhare_brand_map' => $censhare_brand_map,
        'map_censhare_variant_product' => $map_censhare_variant_product,
        'brand_map' => $brand_map
      );
    }
  }
