<?php

namespace Atlantis\Helpers;

use Illuminate\Support\Facades\File;

class Tools {
  /*
   * Checks if "mobile" is part of the current url 
   */

  public static function checkURLforMobile($url) {

    preg_match("/\mobile\/(.*?)/", $url, $match);

    if (empty($match)) {
      return false;
    } else {
      return true;
    }
  }

  public static function makeAppParamsFromString($string, $aAttr = array()) {

    if (!empty($string)) {

      $aCall = explode("@", $string);

      if (isset($aCall[0]) || !empty($aCall[0])) {

        $aClass = explode(":", $aCall[0]);

        foreach ($aClass as $key => $value) {
          $aClass[$key] = ucfirst($value);
        }

        $sClass = implode("\\", $aClass);

        if (isset($aCall[1]) || !empty($aCall[1])) {

          $aFunc = explode("-", $aCall[1]);

          $sFunc = $aFunc[0];

          if (isset($aFunc[1]) || !empty($aFunc[1])) {

            $params = explode(",", $aFunc[1]);
          }

          $params['attributes'] = $aAttr;

          return [
              'class' => $sClass,
              'func' => $sFunc,
              'params' => $params
          ];
        } else {
          return FALSE;
        }
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

  /**
   *  Converts
   * 
   *  blog@latest-10
   *  modules:blog@latest-10
   *  atlantis:blog:customController@latest-10
   * 
   *  to a proper app call 
   */
  public static function makeAppCallFromString($string, $aAttr = array()) {

    $data = self::makeAppParamsFromString($string, $aAttr);

    if ($data === FALSE) {
      return abort(404, "call " . $string . " can't be loaded");
    }
    if (self::isBeforePHP7()) {
      return \App::make($data['class'])->$data['func']($data['params']);
    } else {
      return \App::make($data['class'])->{$data['func']}($data['params']);
    }
  }

  public static function arr2list($array) {

    return implode(", ", $array);
  }

  /*
   * Set variable in .env file or edit
   */

  public static function setDotenvVar($variable, $value) {

    $aFile = file(".env");

    $newVar = $variable . "=" . $value . "\n";

    if (getenv($variable)) {
      //replace
      foreach ($aFile as $k => $line) {

        if (stristr($line, $variable)) {
          $aFile[$k] = $newVar;
        }
      }
    } else {
      //add variable
      array_push($aFile, $newVar);
    }

    file_put_contents(".env", implode("", $aFile));
  }

  /*
   * MULTI SITES
   * 
   * Check if this site is master
   */

  public static function isMasterSite($multiSitesConfig) {

    $domain = request()->root();

    foreach ($multiSitesConfig['sites'] as $key => $site) {

      if ($site['master'] && $site['domain'] == $domain) {

        return TRUE;
      }
    }

    return FALSE;
  }

  /*
   * MULTI SITES
   * 
   * Get master site from multi-sites.php
   */

  public static function getMasterSite($multiSitesConfig) {

    foreach ($multiSitesConfig['sites'] as $key => $site) {

      if ($site['master']) {

        return $site;
      }
    }

    return NULL;
  }

  /*
   * Convert string to folder name
   */

  public static function stringToFolderName($string) {

    return preg_replace("/[^a-zA-Z]+/", "", $string);
  }

  public static function getModulesByType($type) {

    $modules = \Atlantis\Models\Repositories\ModulesRepository::getModulesWithExtra();

    $aModules = array();

    foreach ($modules as $module) {

      $extra = unserialize($module->extra);

      if (!empty($extra) && is_array($extra)) {

        if (isset($extra['type']) && $extra['type'] == $type) {
          $aModules[$module->id]['name'] = $module->name;
          $aModules[$module->id]['author'] = $module->author;
          $aModules[$module->id]['version'] = $module->version;
          $aModules[$module->id]['namespace'] = $module->namespace;
          $aModules[$module->id]['path'] = $module->path;
          $aModules[$module->id]['provider'] = $module->privder;
          $aModules[$module->id]['namespace'] = $module->namespace;
          $aModules[$module->id]['extra'] = $extra;
          $aModules[$module->id]['description'] = $module->description;
        }
      }
    }

    return $aModules;
  }

  public static function getExpirationDateForView($date) {

    if ($date != NULL) {

      $dt = \Carbon\Carbon::createFromFormat(\Carbon\Carbon::DEFAULT_TO_STRING_FORMAT, $date);

      return $dt->format(\Atlantis\Models\Repositories\PageRepository::$_EXPIRATION_DATE_FORMAT_VIEW);
    } else {
      return NULL;
    }
  }

  public static function getModuleFileSetup($path) {

    $dir_iterator = new \RecursiveDirectoryIterator(base_path() . config('atlantis.modules_dir') . $path);

    $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

    $regex = new \RegexIterator($iterator, '/.+Setup\.php$/i', \RecursiveRegexIterator::GET_MATCH);

    $setup = array();

    foreach ($regex as $r) {

      $setup = require($r[0]);
    }

    return $setup;
  }

  /**
   * Get all names of templates
   * and return in array.
   */
  public static function getTemplates() {

    $aT[NULL] = '';

    if (Themes\ThemeTools::haveActiveTheme()) {

      $aTemp = Iterator::getFiles('/' . config('atlantis.theme_path') . "/views/page", "WITHOUT EXT", TRUE, FALSE);

      foreach ($aTemp as $temp) {

        $aElem = explode("/", $temp);

        if (!in_array("disabled", $aElem)) {

          $stripTemp = str_replace('.blade', '', $temp);

          $aT[$stripTemp] = $stripTemp;
        }
      }
    }
    return $aT;
  }

  public static function getEditors() {

    $ModulesWithExtra = \Atlantis\Models\Repositories\ModulesRepository::getModulesWithExtra();

    $aEditors = array();

    foreach ($ModulesWithExtra as $mod) {
      $extra = unserialize($mod->extra);

      if (is_array($extra) && isset($extra['type']) && $extra['type'] == 'editor' && isset($extra['editorClass'])) {
        $aEditors[$extra['editorClass']] = $mod->name;
      }
    }
    return $aEditors;
  }

  public static function getCommentEngines()
  {
      $ModulesWithExtra = \Atlantis\Models\Repositories\ModulesRepository::getModulesWithExtra();

      $aCommentEngines = array();

      foreach ($ModulesWithExtra as $mod)
      {
          $extra = unserialize($mod->extra);

          if (is_array($extra) && isset($extra['type']) && $extra['type'] == 'comments' && isset($extra['commentsClass']))
          {
              $aCommentEngines[$extra['commentsClass']] = $mod->name;
          }
      }
      return $aCommentEngines;
  }

  public static function getThemeLanguages() {

    $dir = config('atlantis.theme_path') . '/lang/';

    if (File::isDirectory($dir)) {

      $langs = File::directories($dir);

      $aLang = array();

      foreach ($langs as $lang) {
        $l = str_replace($dir . '/', '', $lang);
        $aLang[$l] = $l;
      }

      return $aLang;
    } else {
      return [];
    }
  }

  public static function getAdminLanguages() {

    $dir = 'vendor/atlantis-labs/atlantis3-framework/src/Atlantis/Languages/';

    if (File::isDirectory($dir)) {

      $langs = File::directories($dir);

      $aLang = array();

      foreach ($langs as $lang) {
        $l = str_replace($dir . '/', '', $lang);
        $aLang[$l] = $l;
      }

      return $aLang;
    } else {
      return [];
    }
  }

  /**
   * 
   * @param string $userMediaUploadPath
   * @return string
   */
  public static function getFilePath($userMediaUploadPath = TRUE) {

    if ($userMediaUploadPath) {
      $path = config('atlantis.user_media_upload');
    } else {
      $path = '';
    }

    if (config('atlantis.use_amazon_cdn')) {
      return config('atlantis.amazon_cloudfront_url') . $path;
    }

    if (config('atlantis.use_amazon_s3')) {

      return config('atlantis.amazon_s3_url') . $path;
    }

    return '/' . $path;
  }

  /**
   * 
   * @return string
   */
  public static function makeAtlantisKey() {
    return \Illuminate\Support\Str::random(32);
  }

  /**
   * 
   * @param string $path
   * @return string
   */
  public static function getParentFolderPath($path) {
    $aPath = array_filter(explode('/', $path));
    array_pop($aPath);

    return implode('/', $aPath);
  }

  /**
   * 
   * @param string $path
   * @param boolean $recursively
   * @return boolean
   */
  public static function isWritableDir($path, $recursively = FALSE) {

    if ($recursively) {
      $disk = \Illuminate\Support\Facades\Storage::disk('local');

      $aDirs = $disk->allDirectories($path);
      $aFiles = $disk->allFiles($path);

      foreach ($aDirs as $dir) {
        if (!is_writable($dir)) {
          return FALSE;
        }
      }

      foreach ($aFiles as $file) {
        if (!is_writable($file)) {
          return FALSE;
        }
      }

      return TRUE;
    } else {
      return is_writable(base_path($path));
    }
  }

  /**
   * 
   * @param int $bytes
   * @param int $precision
   * @return string
   */
  public static function formatBytes($bytes, $precision = 2) {

    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow];
  }

  /**
   * 
   * @param string $anchorUrl
   * @param string $path
   * @return array
   */
  public static function getAnchorUrlParams($anchorUrl, $path) {

    $params = implode('', explode($anchorUrl, $path, 2));
    $aParams = array_filter(explode('/', $params));

    return $aParams;
  }

  /**
   * 
   * @return boolean
   */
  public static function isBeforePHP7() {
    return (version_compare(phpversion(), "7.0.0", "<="));
  }

  public static function getFrameworkVersion()
  {
      if(is_file(base_path()."/composer.lock"))
      {
          $lock = json_decode(file_get_contents(base_path()."/composer.lock"));

          foreach($lock->packages as $info)
          {
              if($info->name == 'atlantis-labs/atlantis3-framework')
              {
                  return $info->version;
              }
          }
      }
      else
      {
          return false;
      }
  }

}
