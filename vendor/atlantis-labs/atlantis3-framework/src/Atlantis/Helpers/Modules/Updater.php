<?php

namespace Atlantis\Helpers\Modules;

use Illuminate\Support\Facades\Storage;

class Updater {

  public static $_UPDATER_STORAGE = 'resources/updater';
  public static $_PUBLISH_STORAGE = 'resources/updater/publish';
  public static $_STATUS_INSTALLED = 'installed';
  public static $_STATUS_DOWNLOADED = 'downloaded';
  public static $_STATUS_ACTIVE = 'available for download';

  public static function checkNewVersions($aModules) {

    $client = new \GuzzleHttp\Client();

    try {
      $res = $client->request('POST', 'http://modules.atlantis-cms.com/api/check-for-new-version', [
          'form_params' => [
              'modules' => json_encode($aModules)
          ]
      ]);
      return json_decode($res->getBody()->getContents(), TRUE);
    } catch (\Exception $e) {
      return $aModules;
    }
  }

  /**
   * downloadModule from modules repository to downloaded folder $_UPDATER_STORAGE
   * 
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public static function downloadFile(\Illuminate\Http\Request $request) {

    $updaterFolder = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . self::$_UPDATER_STORAGE;

    if (!Storage::disk('local')->makeDirectory(self::$_UPDATER_STORAGE)) {
      return ['error' => 'Folder "' . $updaterFolder . '/' . '" can not be created.'];
    }

    $client = new \GuzzleHttp\Client();

    try {
      $time = time();
      $filenameTmp = $time . '.tmp';
      $resource = fopen($updaterFolder . '/' . $filenameTmp, 'w');
      $stream = \GuzzleHttp\Psr7\stream_for($resource);
      $res = $client->request('POST', 'http://modules.atlantis-cms.com/api/download-file', [
          'form_params' => [
              'version' => $request->get('version'),
              'path' => $request->get('path'),
              'namespace' => $request->get('namespace')
          ],
          'sink' => $stream
      ]);
      //dd($res->getBody()->getContents());
      $filesizeTmp = Storage::disk('local')->size(self::$_UPDATER_STORAGE . '/' . $filenameTmp);

      if ($res->getStatusCode() == 200 && isset($res->getHeader('Filename')[0]) && isset($res->getHeader('Filesize')[0]) && $res->getHeader('Filesize')[0] == $filesizeTmp) {

        $filename = $time . '-' . $res->getHeader('Filename')[0];

        if (!Storage::disk('local')->move(self::$_UPDATER_STORAGE . '/' . $filenameTmp, self::$_UPDATER_STORAGE . '/' . $filename)) {

          Storage::disk('local')->delete(self::$_UPDATER_STORAGE . '/' . $filenameTmp);
          return ['error' => 'Download failed'];
        }

        return [
            'success' =>
            [
                'filename' => $filename
            ]
        ];
      } else {

        Storage::disk('local')->delete(self::$_UPDATER_STORAGE . '/' . $filenameTmp);
        return ['error' => 'Download failed'];
      }
    } catch (\Exception $e) {

      Storage::disk('local')->delete(self::$_UPDATER_STORAGE . '/' . $filenameTmp);
      return ['error' => 'Download failed'];
    }
  }

  /**
   * Replace installed module with new downloaded
   * 
   * @param \Illuminate\Http\Request $request
   * @param String $filename
   * @return array
   */
  public static function replaceModule(\Illuminate\Http\Request $request, $filename) {

    $oModule = \Atlantis\Models\Repositories\ModulesRepository::getModule($request->get('namespace'))->first();

    if ($oModule != NULL) {

      /**
       * disable module
       */
      $oModule->active = 0;
      $oModule->update();

      $modulePath = str_replace('/', '', config('atlantis.modules_dir')) . '/' . \Atlantis\Helpers\Tools::getParentFolderPath($oModule->path);
      $moduleBackupPath = $modulePath . '-backup';
      $filenameBackup = 'backup-' . $filename;
      $moduleFolder = last(array_filter(explode('/', $modulePath)));

      if ($moduleFolder === FALSE) {
        return ['error' => 'Module folder not found'];
      }

      /**
       * create backup archive
       */
      Storage::disk('local')->move($modulePath, $moduleBackupPath);
      \Zipper::make(base_path(self::$_UPDATER_STORAGE) . '/' . $filenameBackup)->folder($moduleFolder)->add(base_path($moduleBackupPath));


      /**
       * unzip new module in modules folder
       */
      Storage::disk('local')->makeDirectory($modulePath);
      \Zipper::make(base_path(self::$_UPDATER_STORAGE) . '/' . $filename)->folder($moduleFolder)->extractTo(base_path($modulePath));

      /**
       * delete unnecessary files
       */
      Storage::disk('local')->deleteDirectory($moduleBackupPath);
      Storage::disk('local')->delete(self::$_UPDATER_STORAGE . '/' . $filename);

      /**
       * update modules table module
       */
      $setup = \Atlantis\Helpers\Tools::getModuleFileSetup($request->get('path'));
      if (empty($setup)) {
        return ['error' => 'Setup.php file not found'];
      }

      /**
       * change module folder permissions
       */
      exec('chmod -R 775 ' . $modulePath);

      /**
       * run migrations
       */
      \Artisan::call('atlantis:migrate:module', [
          'namespace' => $request->get('namespace')
      ]);

      $oModule->name = $setup['name'];
      $oModule->author = $setup['author'];
      $oModule->version = $setup['version'];
      $oModule->namespace = $setup['moduleNamespace'];
      $oModule->path = $setup['path'];
      $oModule->provider = $setup['provider'];
      $oModule->extra = serialize($setup['extra']);
      $oModule->adminURL = $setup['adminURL'];
      $oModule->icon = $setup['icon'];
      $oModule->description = $setup['description'];
      $oModule->active = 1;
      $oModule->update();
    } else {
      return ['error' => 'Module namespace not found'];
    }
  }

  /**
   * 
   * Unzip and move module from downloaded folder ($_UPDATER_STORAGE) to /modules
   * 
   * @param \Illuminate\Http\Request $request
   * @param String $filename
   * @return array
   */
  public static function moveModule(\Illuminate\Http\Request $request, $filename) {

    if ($request->get('path') != NULL) {

      $modulePath = str_replace('/', '', config('atlantis.modules_dir')) . '/' . \Atlantis\Helpers\Tools::getParentFolderPath($request->get('path'));
      $moduleFolder = last(array_filter(explode('/', $modulePath)));

      if ($moduleFolder == config('atlantis.modules_dir') || empty($moduleFolder)) {
        return ['error' => 'Invalid module path'];
      }

      /**
       * unzip new module in modules folder
       */
      Storage::disk('local')->makeDirectory($modulePath);
      \Zipper::make(base_path(self::$_UPDATER_STORAGE) . '/' . $filename)->folder($moduleFolder)->extractTo(base_path($modulePath));

      /**
       * delete unnecessary files
       */
      Storage::disk('local')->delete(self::$_UPDATER_STORAGE . '/' . $filename);

      /**
       * change module folder permissions
       */
      exec('chmod -R 775 ' . $modulePath);
    } else {
      return ['error' => 'Invalid module path'];
    }
  }

}
