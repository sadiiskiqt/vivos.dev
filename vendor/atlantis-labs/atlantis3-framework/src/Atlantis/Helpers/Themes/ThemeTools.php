<?php

namespace Atlantis\Helpers\Themes;

class ThemeTools {

  public static function getAllConfigs() {

    $path = 'resources/themes';
    $base_path = base_path() . '/';

    $dir_iterator = new \RecursiveDirectoryIterator($base_path . $path);

    $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

    $regex = new \RegexIterator($iterator, '/.+config\.php$/i', \RecursiveRegexIterator::GET_MATCH);

    $config = array();

    foreach ($regex as $r) {

      $config[str_replace($base_path, '', dirname($r[0]))] = require($r[0]);
    }

    return $config;
  }

  public static function haveActiveTheme() {

    $theme_path = config('atlantis.theme_path');

    if (!empty($theme_path) && $theme_path != NULL && is_dir(base_path() . "/" . $theme_path)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public static function getFullThemePath() {

    if (self::haveActiveTheme()) {
      return base_path() . "/" . config('atlantis.theme_path');
    } else {
      return NULL;
    }
  }

  public static function getPatternVariables($path = NULL) {

    if ($path == NULL) {
      $path = config('atlantis.theme_path');
    }

    return require($path . '/config.php');
  }

  /**
   * Use str_slug() to generate url string from theme name.
   * 
   * $url_name = str_slug($themeName);
   * 
   * @param String $url_name
   */
  public static function getThemeConfigByUrlName($url_name) {

    $allConfigs = self::getAllConfigs();
    
    foreach ($allConfigs as $theme_path => $config) {
      if (is_array($config) && isset($config['name']) && str_slug($config['name']) == $url_name) {
        $config['_theme_path'] = $theme_path;
        return $config;
      }
    }

    return NULL;
  }

}
