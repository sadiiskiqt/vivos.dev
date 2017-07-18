<?php

namespace Atlantis\Helpers;

use Illuminate\Support\Facades\Storage;

class SyncFilesV2 {

  private $configRepo;
  private $backupFolder = '/resources/sync';
  private $cloudFrontVer = '2015-07-27';
  private $chunkSize = 10000000;
  private $s3FileHeaders = [
      //'visibility' => 'public',
      'CacheControl' => 'max-age=604800'
  ];

  public function __construct() {

    $this->configRepo = new \Atlantis\Models\Repositories\ConfigRepository();
  }

  public function manageDirs($dirs, $disk) {

    $dirs = $this->fidDirs($dirs, $disk);

    if (empty($dirs['errors'])) {
      $this->configRepo->addConfig('s3_sync_dirs', $dirs['sync']);

      $files = array();
      foreach ($dirs['all'] as $dir) {
        $files = array_merge($files, Storage::disk($disk)->allFiles($dir));
      }

      $files = array_unique(array_filter($files));

      return ['files' => array_values($files)];
    } else {
      return ['error' => $dirs['errors']];
    }
  }

  public function localToS3($files, $dirs, $type) {

    $last_sync_to_cloud = config('atlantis.last_sync_to_cloud');

    if (empty($files)) {

      $this->configRepo->addConfig('last_sync_to_cloud', time());
      return $this->clearFiles($files, $dirs, $type);
    }

    $add = array();
    $update = array();

    $size = 0;

    foreach ($files as $k => $file) {

      $fileSize = Storage::disk('local')->size($file);

      if (Storage::disk('s3')->has($file)) {

        $modified = Storage::disk('local')->lastModified($file);

        if ($modified > $last_sync_to_cloud) {
          //update
          $update[] = $file;
          if ($fileSize > $this->chunkSize) {
            $org_file = Storage::disk('local')->getDriver()->readStream($file);
          } else {
            $org_file = Storage::disk('local')->get($file);
          }

          Storage::disk('s3')->delete($file);
          //Storage::disk('s3')->put($file, $org_file);

          Storage::disk('s3')->getDriver()->put($file, $org_file, $this->s3FileHeaders);
        }
      } else {
        //add
        $add[] = $file;

        if ($fileSize > $this->chunkSize) {
          $org_file = Storage::disk('local')->getDriver()->readStream($file);
        } else {
          $org_file = Storage::disk('local')->get($file);
        }

        //Storage::disk('s3')->put($file, $org_file);

        Storage::disk('s3')->getDriver()->put($file, $org_file, $this->s3FileHeaders);
      }

      $size = $size + $fileSize;

      unset($files[$k]);

      if ($size > $this->chunkSize) {

        return ['files' => array_values($files), 'added' => $add, 'update' => $update, 'size' => $size];
      }
    }

    return ['files' => array_values($files), 'added' => $add, 'update' => $update, 'size' => $size];
  }

  public function s3ToLocal($files, $dirs, $type) {

    $last_sync_to_local = config('atlantis.last_sync_to_local');

    if (empty($files)) {

      $this->configRepo->addConfig('last_sync_to_local', time());
      return $this->clearFiles($files, $dirs, $type);
    }

    $add = array();
    $update = array();

    $size = 0;

    foreach ($files as $k => $file) {

      $fileSize = Storage::disk('s3')->size($file);

      if (Storage::disk('local')->has($file)) {

        $modified = Storage::disk('s3')->lastModified($file);

        if ($modified > $last_sync_to_local) {
          //update
          $update[] = $file;

          if ($fileSize > $this->chunkSize) {
            $org_file = Storage::disk('s3')->getDriver()->readStream($file);
          } else {
            $org_file = Storage::disk('s3')->get($file);
          }

          Storage::disk('local')->delete($file);
          Storage::disk('local')->put($file, $org_file);
        }
      } else {
        //add
        $add[] = $file;
        if ($fileSize > $this->chunkSize) {
          $org_file = Storage::disk('s3')->getDriver()->readStream($file);
        } else {
          $org_file = Storage::disk('s3')->get($file);
        }

        Storage::disk('local')->put($file, $org_file);
      }

      $size = $size + $fileSize;
      unset($files[$k]);

      if ($size > $this->chunkSize) {

        return ['files' => array_values($files), 'added' => $add, 'update' => $update, 'size' => $size];
      }
    }

    return ['files' => array_values($files), 'added' => $add, 'update' => $update, 'size' => $size];
  }

  private function fidDirs($dirs, $disk) {

    $aDirs = array_filter(explode("\n", $dirs));

    $s3_sync_dirs = $aDirs;

    $aErrors = array();

    foreach ($aDirs as $k => $v) {
      $path = implode('/', array_filter(explode('/', trim($v)))) . '/';

      if (!Storage::disk($disk)->has($path)) {
        $aErrors[] = '"' . $v . '" is invalid ' . strtoupper($disk) . ' path.';
      }

      $aDirs[$k] = $path;

      if ($path == config('atlantis.user_media_upload')) {
        unset($s3_sync_dirs[$k]);
      }
    }

    return ['all' => array_unique($aDirs), 'sync' => array_unique($s3_sync_dirs), 'errors' => $aErrors];
  }

  private function clearFiles($files, $dirs, $type) {

    $localFiles = $this->manageDirs($dirs, 'local')['files'];
    $cloudFieles = $this->manageDirs($dirs, 's3')['files'];

    $deleted = array();

    $backupTime = time();

    if ($type == 'to_local') {

      foreach ($localFiles as $file) {

        if (!in_array($file, $cloudFieles)) {
          //delete
          $deleted[] = $file;

          $fileSize = Storage::disk('local')->size($file);

          if ($fileSize > $this->chunkSize) {
            $fileCont = Storage::disk('local')->getDriver()->readStream($file);
          } else {
            $fileCont = Storage::disk('local')->get($file);
          }

          $this->makeBackup($file, $fileCont, $backupTime, FALSE);
          Storage::disk('local')->delete($file);
        }
      }
    } else if ($type == 'to_s3') {

      foreach ($cloudFieles as $file) {

        if (!in_array($file, $localFiles)) {
          //delete
          $deleted[] = $file;

          $fileSize = Storage::disk('s3')->size($file);

          if ($fileSize > $this->chunkSize) {
            $fileCont = Storage::disk('s3')->getDriver()->readStream($file);
          } else {
            $fileCont = Storage::disk('s3')->get($file);
          }

          $this->makeBackup($file, $fileCont, $backupTime, TRUE);
          Storage::disk('s3')->delete($file);
        }
      }
    }

    return ['success' => 'Done!', 'deleted' => $deleted];
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
