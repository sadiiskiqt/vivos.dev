<?php

namespace Atlantis\Helpers;

use Illuminate\Support\Facades\Storage;

class SyncFiles {

  private $user_media_upload;
  private $configRepo;
  private $syncDirs = [];
  private $backupFolder = '/resources/sync';
  private $cloudFrontVer = '2015-07-27';

  public function __construct() {

    $this->user_media_upload = config('atlantis.user_media_upload');
    $this->configRepo = new \Atlantis\Models\Repositories\ConfigRepository();
    
    $this->syncDirs = config('atlantis.s3_sync_dirs');
    if (is_array($this->syncDirs)) {
      $this->syncDirs = array_prepend($this->syncDirs, config('atlantis.user_media_upload'));
    } else {
      $this->syncDirs[] = config('atlantis.user_media_upload');
    }
  }

  public function manageDirs($dirs, $disk) {

    $aDirs = array_filter(explode("\n", $dirs));

    $aErrors = array();

    foreach ($aDirs as $k => $v) {
      $path = implode('/', array_filter(explode('/', trim($v)))) . '/';

      if (!Storage::disk($disk)->has($path)) {
        $aErrors[] = '"' . $v . '" is invalid ' . strtoupper($disk) . ' path.';
      }

      if ($path == config('atlantis.user_media_upload')) {
        unset($aDirs[$k]);
      } else {
        $aDirs[$k] = $path;
      }
    }

    $aDirs = array_unique($aDirs);

    if (empty($aErrors)) {
      $this->configRepo->addConfig('s3_sync_dirs', $aDirs);
      $this->syncDirs = array_prepend($aDirs, config('atlantis.user_media_upload'));
      return ['success' => 'Done!'];
    } else {
      return ['error' => $aErrors];
    }
  }

  public function localToS3() {

    $last_sync_to_cloud = config('atlantis.last_sync_to_cloud');

    foreach ($this->syncDirs as $dir) {
      $localFiles = Storage::disk('local')->allFiles($dir);
      $cloudFieles = Storage::disk('s3')->allFiles($dir);

      //$add = array();
      //$update = array();
      //$delete = array();

      $backupTime = time();

      foreach ($localFiles as $file) {

        if (in_array($file, $cloudFieles)) {
          $modified = Storage::disk('local')->lastModified($file);
          if ($modified > $last_sync_to_cloud) {
            //update
            //$update[] = $file;
            $org_file = Storage::disk('local')->get($file);
            Storage::disk('s3')->delete($file);
            Storage::disk('s3')->put($file, $org_file);
          }
        } else {
          //add
          //$add[] = $file;
          $org_file = Storage::disk('local')->get($file);
          Storage::disk('s3')->put($file, $org_file);
        }
      }

      foreach ($cloudFieles as $file) {

        if (!in_array($file, $localFiles)) {
          //delete
          //$delete[] = $file;
          $this->makeBackup($file, Storage::disk('s3')->get($file), $backupTime, TRUE);
          Storage::disk('s3')->delete($file);
        }
      }
    }

    exec('chmod -R 775 ' . base_path($this->backupFolder));

    $this->configRepo->addConfig('last_sync_to_cloud', time());
  }

  public function s3ToLocal() {

    $last_sync_to_local = config('atlantis.last_sync_to_local');

    foreach ($this->syncDirs as $dir) {
      $localFiles = Storage::disk('local')->allFiles($dir);
      $cloudFieles = Storage::disk('s3')->allFiles($dir);

      $add = array();
      $update = array();
      $delete = array();

      $backupTime = time();

      foreach ($cloudFieles as $file) {

        if (in_array($file, $localFiles)) {
          $modified = Storage::disk('s3')->lastModified($file);
          if ($modified > $last_sync_to_local) {
            //update
            $update[] = $file;
            $org_file = Storage::disk('s3')->get($file);
            Storage::disk('local')->delete($file);
            Storage::disk('local')->put($file, $org_file);
          }
        } else {
          //add
          $add[] = $file;
          $org_file = Storage::disk('s3')->get($file);
          Storage::disk('local')->put($file, $org_file);
        }
      }

      foreach ($localFiles as $file) {

        if (!in_array($file, $cloudFieles)) {
          //delete
          $delete[] = $file;
          $this->makeBackup($file, Storage::disk('local')->get($file), $backupTime, FALSE);
          Storage::disk('local')->delete($file);
        }
      }

      /**
        $localDirs = Storage::disk('local')->allDirectories($dir);

        foreach ($localDirs as $dir) {
        //rmdir(base_path($dir));
        }

        dd($localDirs);
       * 
       */
    }

    exec('chmod -R 775 ' . base_path($this->backupFolder));

    $this->configRepo->addConfig('last_sync_to_local', time());
  }

  private function makeBackup($filePath, $fileCont, $time, $sync_to_cloud = FALSE) {

    $folder = $sync_to_cloud ? '/to_cloud' : '/to_local';

    $backupFilePath = $this->backupFolder
            . $folder
            . '/' . $time
            . '/' . $filePath;

    if (Storage::disk('local')->has($backupFilePath)) {
      Storage::disk('local')->delete($backupFilePath);
    }

    Storage::disk('local')->put($backupFilePath, $fileCont);
  }

  public function invalidateFiles($files) {

    $count = count($files);

    if ($count > 0) {

      $client = \Aws\CloudFront\CloudFrontClient::factory([
                  'version' => $this->cloudFrontVer,
                  'region' => config('filesystems.disks.s3.region'),
                  'credentials' => [
                      'key' => config('filesystems.disks.s3.key'),
                      'secret' => config('filesystems.disks.s3.secret'),
                  ]
      ]);

      $result = $client->createInvalidation([
          // DistributionId is required
          'DistributionId' => config('filesystems.disks.s3.distribution_id'),
          'InvalidationBatch' => [
              // Paths is required
              'Paths' => [
                  // Quantity is required
                  'Quantity' => $count,
                  'Items' => $files,
              ],
              // CallerReference is required
              'CallerReference' => 'inv-atlantis-cms-' . time(),
          ]
      ]);
      return $result;
    }
    return NULL;
  }

}
