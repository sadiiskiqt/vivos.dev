<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Helpers\Themes\ThemeTools;
use Illuminate\Http\Request;
use Atlantis\Models\Repositories\ConfigRepository;

class ThemesController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_THEMES);
  }

  public function getIndex() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }

    $themeConfigs = ThemeTools::getAllConfigs();

    $aThemes[1] = array();
    $aThemes[2] = array();
    $aThemes[3] = array();
    $aThemes[4] = array();

    $row = 1;

    $i = 0;

    foreach ($themeConfigs as $path => $config) {

      if (isset($config['name']) && isset($config['version'])) {

        $aThemes[$row][$i]['config'] = $config;
        $aThemes[$row][$i]['path'] = $path;
        
        if (config('atlantis.theme_path') == $path) {        
        $aThemes[$row][$i]['active'] = TRUE;        
        } else {
          $aThemes[$row][$i]['active'] = FALSE;
        }

        $i++;

        if ($row == 4) {
          $row = 1;
        } else {
          $row++;
        }
      }
    }
    
    $aData['aThemes'] = $aThemes;
    $aData['count_installed'] = count($aThemes[1]) + count($aThemes[2]) + count($aThemes[3]) + count($aThemes[4]);

    return view('atlantis-admin::themes', $aData);
  }
  
  public function getDetails($name = NULL) {
    
    $aData = array();
    
    if (\Session::get('info') != NULL) {
      $aData['msgInfo'] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'] = \Session::get('error');
    }
    
    $aData['themeConfig'] = ThemeTools::getThemeConfigByUrlName($name);
    
    return view('atlantis-admin::themes-details', $aData);
    
  }


  public function postActivateTheme(Request $request) {
    
    if ($request->get('theme_path') != NULL) {
      $oConfig = new ConfigRepository();
      $oConfig->addConfig('theme_path', $request->get('theme_path'));
      
      \Session::flash('success', 'Theme was activated');      
    }
    return redirect()->back();
  }
  
   public static function postDeactivateTheme(Request $request) {
     
    if ($request->get('theme_path') != NULL) {
      $oConfig = new ConfigRepository();
      
      $oConfig->addConfig('theme_path', NULL);
      
      \Session::flash('success', 'Theme was deactivated');      
    }
    return redirect()->back();
  }

}
