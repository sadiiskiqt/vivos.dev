<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Models\Repositories\ModulesRepository;
use Atlantis\Helpers\Modules\Updater as ModuleUpdater;
use Atlantis\Helpers\Tools;

class ModulesController extends AdminController {

  private $is_admin = FALSE;

  public function __construct() {

    parent::__construct(self::$_ID_MODULES);

    if (!empty(auth()->user())) {
      $this->is_admin = auth()->user()->hasRole('admin');
    }
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

    $userPermissions = \Atlantis\Models\Repositories\PermissionsRepository::getAllModulesPermissionsForUser(auth()->user()->id);
    $aUserPerm = array();
    foreach ($userPermissions as $user_perm) {
      $aUserPerm[] = $user_perm->value;
    }

    $aUserPerm = array_unique($aUserPerm);

    $modules = ModulesRepository::getAllModules()->toArray();

    $aMod = array();
    $aModProtected = array();

    $aData['needUpdate'] = FALSE;

    foreach ($modules as $module) {

      if ($this->isAllowed($aUserPerm, $module['namespace'])) {

        $fileSetup = Tools::getModuleFileSetup($module['path']);

        if ($this->isChangedSetup($module, $fileSetup)) {
          $aData['needUpdate'] = TRUE;
        }

        if ($module['icon'] != NULL || !empty($module['icon'])) {

          if (isset($fileSetup['protected']) && $fileSetup['protected']) {
            $module['protected'] = $fileSetup['protected'];
            $aModProtected[] = $module;
          } else {
            $aMod[] = $module;
          }
        }
      }
    }

    $aMod = ModuleUpdater::checkNewVersions($aMod);

    $aMod = array_merge($aModProtected, $aMod);

    $aModules[1] = array();
    $aModules[2] = array();
    $aModules[3] = array();

    $row = 1;

    foreach ($aMod as $mod) {

      $aModules[$row][] = $mod;

      if ($row == 3) {
        $row = 1;
      } else {
        $row++;
      }
    }

    //dd($aModules);
    $aData['aModules'] = $aModules;
    $aData['count_installed'] = count($aModules[1]) + count($aModules[2]) + count($aModules[3]);

    $aNotInstalled[1] = array();
    $aNotInstalled[2] = array();
    $aNotInstalled[3] = array();

    $row = 1;

    foreach ($this->getNotInstalledModules() as $module) {

      $aNotInstalled[$row][] = $this->fitModuleSetup($module);

      if ($row == 3) {
        $row = 1;
      } else {
        $row++;
      }
    }

    $aData['aNotInstalledModules'] = $aNotInstalled;
    $aData['count_notinstalled'] = count($aNotInstalled[1]) + count($aNotInstalled[2]) + count($aNotInstalled[3]);
    $aData['canEditModules'] = $this->canEditModules();

    return view('atlantis-admin::modules', $aData);
  }

  public function getActivateModule($id = NULL) {

    if ($this->canEditModules()) {

      $module = ModulesRepository::getInstalledModuleByID($id);

      if ($module != NULL) {

        $module->active = 1;
        $module->save();

        \Session::flash('success', 'Module ' . $module->name . ' was activated');
      } else {
        \Session::flash('error', 'Invalid module');
      }
    }
    return redirect()->back();
  }

  public function getDeactivateModule($id = NULL) {

    if ($this->canEditModules()) {

      $module = ModulesRepository::getInstalledModuleByID($id);

      if ($module != NULL) {

        $module->active = 0;
        $module->save();

        \Session::flash('success', 'Module ' . $module->name . ' was deactivated');
      } else {
        \Session::flash('error', 'Invalid module');
      }
    }
    return redirect()->back();
  }

  public function getUpdateModulesSetup() {

    $modules = ModulesRepository::getAllModules();

    foreach ($modules as $module) {

      $fileSetup = Tools::getModuleFileSetup($module->path);

      if ($this->isChangedSetup($module, $fileSetup)) {

        $fileSetup['extra'] = serialize($fileSetup['extra']);
        $module->update($fileSetup);
      }
    }

    \Session::flash('success', 'Successful');

    return redirect()->back();
  }

  public function postInstall(\Illuminate\Http\Request $request) {

    if ($this->canEditModules()) {
      $moduleSetup = Tools::getModuleFileSetup($request->get('module_path'));

      $installer = new \Atlantis\Helpers\Modules\Installer();
      $installer->install($moduleSetup);

      \Session::flash('success', 'Module "' . $moduleSetup['name'] . '" was successfuly installed!');
    }
    return redirect()->back();
  }

  public function postUninstall($id = NULL) {

    if ($this->canEditModules()) {

      $uninstaller = new \Atlantis\Helpers\Modules\Uninstaller($id);
      $uninstaller->uninstall();

      $messages = '';

      foreach ($uninstaller->getMessages() as $message) {
        $messages .= $message . '<br>';
      }

      if ($uninstaller->isSuccessful()) {
        \Session::flash('success', $messages);
      } else {
        \Session::flash('error', $messages);
      }
    }

    return redirect()->back();
  }

  public function postUpdate(\Illuminate\Http\Request $request) {

    if (Tools::isWritableDir(Tools::getParentFolderPath(config('atlantis.modules_dir') . $request->get('path')), TRUE)) {

      $result = ModuleUpdater::downloadFile($request);

      if (isset($result['success'])) {

        $res = ModuleUpdater::replaceModule($request, $result['success']['filename']);

        if (isset($res['error'])) {
          \Session::flash('error', $res['error']);
        } else {
          \Session::flash('success', 'Update completed');
        }
      } else if (isset($result['error'])) {
        \Session::flash('error', $result['error']);
      } else {
        \Session::flash('error', 'Unexpected error');
      }
    } else {
      \Session::flash('error', 'Permissions denied');
    }
    return redirect()->back();
  }

  private function isChangedSetup($module, $fileSetup) {

    $changed = FALSE;

    if (isset($fileSetup['name']) && $module['name'] != $fileSetup['name']) {
      $changed = TRUE;
    } else if (isset($fileSetup['author']) && $module['author'] != $fileSetup['author']) {
      $changed = TRUE;
    } else if (isset($fileSetup['version']) && $module['version'] != $fileSetup['version']) {
      $changed = TRUE;
    } else if (isset($fileSetup['moduleNamespace']) && $module['namespace'] != $fileSetup['moduleNamespace']) {
      $changed = TRUE;
    } else if (isset($fileSetup['path']) && $module['path'] != $fileSetup['path']) {
      $changed = TRUE;
    } else if (isset($fileSetup['provider']) && $module['provider'] != $fileSetup['provider']) {
      $changed = TRUE;
    } else if (isset($fileSetup['extra']) && $module['extra'] != serialize($fileSetup['extra'])) {
      $changed = TRUE;
    } else if (isset($fileSetup['adminURL']) && $module['adminURL'] != $fileSetup['adminURL']) {
      $changed = TRUE;
    } else if (isset($fileSetup['icon']) && $module['icon'] != $fileSetup['icon']) {
      $changed = TRUE;
    } else if (isset($fileSetup['description']) && $module['description'] != $fileSetup['description']) {
      $changed = TRUE;
    }

    return $changed;
  }

  private function fitModuleSetup($fileSetup) {

    if (!isset($fileSetup['name'])) {
      $fileSetup['name'] = NULL;
    }
    if (!isset($fileSetup['author'])) {
      $fileSetup['author'] = NULL;
    }
    if (!isset($fileSetup['version'])) {
      $fileSetup['version'] = NULL;
    }
    if (!isset($fileSetup['moduleNamespace'])) {
      $fileSetup['moduleNamespace'] = NULL;
    }
    if (!isset($fileSetup['path'])) {
      $fileSetup['path'] = NULL;
    }
    if (!isset($fileSetup['provider'])) {
      $fileSetup['provider'] = NULL;
    }
    if (!isset($fileSetup['extra'])) {
      $fileSetup['extra'] = NULL;
    }
    if (!isset($fileSetup['adminURL'])) {
      $fileSetup['adminURL'] = NULL;
    }
    if (!isset($fileSetup['icon'])) {
      $fileSetup['icon'] = NULL;
    }
    if (!isset($fileSetup['description'])) {
      $fileSetup['description'] = NULL;
    }

    return $fileSetup;
  }

  private function getNotInstalledModules() {

    $aNotInstalled = array();

    if ($this->canEditModules()) {

      $installer = new \Atlantis\Helpers\Modules\Installer();
      $available = $installer->showAvailableModules();

      foreach ($available as $details) {

        if (!$installer->isInstalled($details[0]['moduleNamespace'])) {

          $aNotInstalled[] = $details[0];
        }
      }
    }
    return $aNotInstalled;
  }

  private function isAllowed($aUserPerm, $namespace) {

    $allow = FALSE;

    if ($this->is_admin || in_array($namespace, $aUserPerm)) {
      $allow = TRUE;
    }

    return $allow;
  }

  private function canEditModules() {
    return $this->is_admin;
  }

  /**
   * Modules Repository
   */
  public function getRepository() {

    if (!$this->is_admin) {
      return redirect('/admin/modules');
    }
    
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

    return view('atlantis-admin::modules-repository', $aData);
  }

  public function postDownloadInstall(\Illuminate\Http\Request $request) {

    if (!$this->is_admin) {
      return redirect('/admin/modules');
    }
    
    if (Tools::isWritableDir(Tools::getParentFolderPath(config('atlantis.modules_dir') . $request->get('path')), TRUE)) {

      /**
       * download module from repository
       */
      $result = ModuleUpdater::downloadFile($request);

      if (isset($result['success'])) {

        /**
         * move module in modules folder
         */
        $res = ModuleUpdater::moveModule($request, $result['success']['filename']);

        if (isset($res['error'])) {
          \Session::flash('error', $res['error']);
        } else {

          /**
           * install module
           */
          $moduleSetup = Tools::getModuleFileSetup($request->get('path'));

          $installer = new \Atlantis\Helpers\Modules\Installer();
          $installer->install($moduleSetup);

          \Session::flash('success', 'Module "' . $moduleSetup['name'] . '" was successfuly installed!');
        }
      } else if (isset($result['error'])) {
        \Session::flash('error', $result['error']);
      } else {
        \Session::flash('error', 'Unexpected error');
      }
    } else {
      \Session::flash('error', 'Permissions denied');
    }
    return redirect()->back();
  }

  public function postUpdateKeys(\Illuminate\Http\Request $request) {

    if (!$this->is_admin) {
      return redirect('/admin/modules');
    }
    
    $keys = explode("\n", $request->get('modules_keys'));

    foreach ($keys as $k => $v) {

      $keys[$k] = trim($v);
    }

    $keys = array_unique(array_filter($keys));

    $config = new \Atlantis\Models\Repositories\ConfigRepository();

    $config->addConfig('modules_keys', $keys);

    \Session::flash('success', 'Keys updated');

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

    return redirect()->back();
  }

}
