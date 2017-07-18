<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Config;

class ConfigRepository {

  public static function getAll() {

    return Config::all();
  }

  public function addConfig($key, $value) {

    $model = Config::firstOrNew(['config_key' => $key]);

    $model->config_key = $key;
    $model->config_value = serialize($value);

    if (isset($model->id)) {
      $model->update();
    } else {
      $model->save();
    }

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
  }

  public function updateConfig($data) {

    $data = $this->fitData($data);

    foreach ($data as $key => $value) {
      $this->addConfig($key, $value);
    }
  }

  public function fitData($data) {

    if (isset($data['_token'])) {
      unset($data['_token']);
    }

    if (isset($data['_update'])) {
      unset($data['_update']);
    }

    if (isset($data['include_title'])) {
      $data['include_title'] = TRUE;
    } else {
      $data['include_title'] = FALSE;
    }

    if (isset($data['show_shortcut_bar'])) {
      $data['show_shortcut_bar'] = TRUE;
    } else {
      $data['show_shortcut_bar'] = FALSE;
    }

    if (isset($data['cache'])) {
      $data['cache'] = TRUE;
    } else {
      $data['cache'] = FALSE;
    }

    if (isset($data['use_amazon_s3'])) {
      $data['use_amazon_s3'] = TRUE;
    } else {
      $data['use_amazon_s3'] = FALSE;
    }

    if (isset($data['use_amazon_cdn'])) {
      $data['use_amazon_cdn'] = TRUE;
    } else {
      $data['use_amazon_cdn'] = FALSE;
    }

    if (isset($data['delete_local_file'])) {
      $data['delete_local_file'] = TRUE;
    } else {
      $data['delete_local_file'] = FALSE;
    }

    if (!empty($data['allowed_image_extensions'])) {
      $data['allowed_image_extensions'] = trim($data['allowed_image_extensions']);
      $data['allowed_image_extensions'] = str_replace(' ', '', $data['allowed_image_extensions']);
      $data['allowed_image_extensions'] = explode(',', $data['allowed_image_extensions']);
    }

    if (!empty($data['allowed_others_extensions'])) {
      $data['allowed_others_extensions'] = trim($data['allowed_others_extensions']);
      $data['allowed_others_extensions'] = str_replace(' ', '', $data['allowed_others_extensions']);
      $data['allowed_others_extensions'] = explode(',', $data['allowed_others_extensions']);
    }

    $data['static_images'] = trim($data['static_images']);

    if (!empty($data['static_images'])) {

      $aStaticImgs = explode("\n", $data['static_images']);
      $data['static_images'] = array();
      foreach ($aStaticImgs as $static_img) {

        $static_img = trim($static_img);

        $static = explode('/', $static_img);

        $fullsize = explode('x', $static[1]);
        $thumb = explode('x', $static[2]);

        $data['static_images'][$static[0]] = [
            'fullsize' => [
                'width' => $fullsize[0],
                'height' => $fullsize[1],
                'crop' => isset($fullsize[2]) && strtolower($fullsize[2]) == 'c' ? TRUE : FALSE
            ],
            'thumbnail' => [
                'width' => $thumb[0],
                'height' => $thumb[1],
                'crop' => isset($thumb[2]) && strtolower($thumb[2]) == 'c' ? TRUE : FALSE
            ]
        ];
      }
    }

    $data['responsive_images'] = trim($data['responsive_images']);

    if (!empty($data['responsive_images'])) {

      $aStaticImgs = explode("\n", $data['responsive_images']);
      $data['responsive_images'] = array();
      foreach ($aStaticImgs as $res_img) {

        $res_img = trim($res_img);

        $responsive = explode('/', $res_img);

        $desktop = explode('x', $responsive[1]);
        $tablet = explode('x', $responsive[2]);
        $phone = explode('x', $responsive[3]);
        $thumb = explode('x', $responsive[4]);

        $data['responsive_images'][$responsive[0]] = [
            'desktop' => [
                'width' => $desktop[0],
                'height' => $desktop[1],
                'crop' => isset($desktop[2]) && strtolower($desktop[2]) == 'c' ? TRUE : FALSE
            ],
            'tablet' => [
                'width' => $tablet[0],
                'height' => $tablet[1],
                'crop' => isset($tablet[2]) && strtolower($tablet[2]) == 'c' ? TRUE : FALSE
            ],
            'phone' => [
                'width' => $phone[0],
                'height' => $phone[1],
                'crop' => isset($phone[2]) && strtolower($phone[2]) == 'c' ? TRUE : FALSE
            ],
            'thumbnail' => [
                'width' => $thumb[0],
                'height' => $thumb[1],
                'crop' => isset($thumb[2]) && strtolower($thumb[2]) == 'c' ? TRUE : FALSE
            ]
        ];
      }
    }
    
    $data['responsive_breakpoints'] = trim($data['responsive_breakpoints']);
    
    if (!empty($data['responsive_breakpoints'])) {
        
        $aBrakePoints = explode("/", $data['responsive_breakpoints']);
        if (isset($aBrakePoints[0]) && isset($aBrakePoints[1])) {
            $data['responsive_breakpoints'] = ['large' => $aBrakePoints[0], 'medium' => $aBrakePoints[1]];
        } else {
            $data['responsive_breakpoints'] = NULL;
        }        
    }
     
    $data['default_styles'] = trim($data['default_styles']);

    if (!empty($data['default_styles'])) {

      $aStyles = explode("\n", $data['default_styles']);
      $data['default_styles'] = array();
      foreach ($aStyles as $style) {

        $data['default_styles'][] = trim($style);
      }
      $data['default_styles'] = array_values(array_filter($data['default_styles']));
    }

    $data['default_scripts'] = trim($data['default_scripts']);

    if (!empty($data['default_scripts'])) {

      $aScripts = explode("\n", $data['default_scripts']);
      $data['default_scripts'] = array();
      foreach ($aScripts as $script) {

        $data['default_scripts'][] = trim($script);
      }
      $data['default_scripts'] = array_values(array_filter($data['default_scripts']));
    }

    $data['excluded_scripts'] = trim($data['excluded_scripts']);

    if (!empty($data['excluded_scripts'])) {

      $aScripts = explode("\n", $data['excluded_scripts']);
      $data['excluded_scripts'] = array();
      foreach ($aScripts as $script) {

        $data['excluded_scripts'][] = trim($script);
      }
      $data['excluded_scripts'] = array_values(array_filter($data['excluded_scripts']));
    }

    return $data;
  }

}
