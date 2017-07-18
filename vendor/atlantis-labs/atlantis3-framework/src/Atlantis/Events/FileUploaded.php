<?php

namespace Atlantis\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FileUploaded extends \Illuminate\Support\Facades\Event {

  use SerializesModels;

  public function subscribe($events) {
    
    if (config('atlantis.use_amazon_s3')) {      
      $events->listen('file.uploaded', 'Atlantis\Events\FileUploaded@filter', 999);
    }
  }

  public function filter($data) {
    
    $added = isset($data['added']) ? $data['added'] : NULL;
    $removed = isset($data['removed']) ? $data['removed'] : NULL;
    $updated = isset($data['updated']) ? $data['updated'] : NULL;

    $deleteLocal = config('atlantis.delete_local_file');
    $user_media_upload = (substr(config('atlantis.user_media_upload'), 0, 1) === '/') ? config('atlantis.user_media_upload') : '/' . config('atlantis.user_media_upload');

    $invalidationFiles = array();

    $s3FileHeaders = [
        //'visibility' => 'public',
        'CacheControl' => 'max-age=604800'
    ];
 
    if (is_array($added)) {

      if (isset($added['original_filename']) && !empty($added['original_filename'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $added['original_filename']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $added['original_filename'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $added['original_filename']);
        }
      }

      if (isset($added['tablet_name']) && !empty($added['tablet_name'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $added['tablet_name']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $added['tablet_name'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $added['tablet_name']);
        }
      }

      if (isset($added['phone_name']) && !empty($added['phone_name'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $added['phone_name']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $added['phone_name'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $added['phone_name']);
        }
      }

      if (isset($added['thumbnail']) && !empty($added['thumbnail'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $added['thumbnail']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $added['thumbnail'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $added['thumbnail']);
        }
      }
    }

    if (is_array($removed)) {

      if (isset($removed['original_filename']) && !empty($removed['original_filename']) && Storage::disk('s3')->has($user_media_upload . $removed['original_filename'])) {
        Storage::disk('s3')->delete($user_media_upload . $removed['original_filename']);
        $invalidationFiles[] = $user_media_upload . $removed['original_filename'];
      }

      if (isset($removed['tablet_name']) && !empty($removed['tablet_name']) && Storage::disk('s3')->has($user_media_upload . $removed['tablet_name'])) {
        Storage::disk('s3')->delete($user_media_upload . $removed['tablet_name']);
        $invalidationFiles[] = $user_media_upload . $removed['tablet_name'];
      }

      if (isset($removed['phone_name']) && !empty($removed['phone_name']) && Storage::disk('s3')->has($user_media_upload . $removed['phone_name'])) {
        Storage::disk('s3')->delete($user_media_upload . $removed['phone_name']);
        $invalidationFiles[] = $user_media_upload . $removed['phone_name'];
      }

      if (isset($removed['thumbnail']) && !empty($removed['thumbnail']) && Storage::disk('s3')->has($user_media_upload . $removed['thumbnail'])) {
        Storage::disk('s3')->delete($user_media_upload . $removed['thumbnail']);
        $invalidationFiles[] = $user_media_upload . $removed['thumbnail'];
      }
    }

    if (is_array($updated)) {

      if (isset($updated['original_filename']) && !empty($updated['original_filename'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $updated['original_filename']);
        Storage::disk('s3')->delete($user_media_upload . $updated['original_filename']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $updated['original_filename'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $updated['original_filename']);
        }
        $invalidationFiles[] = $user_media_upload . $updated['original_filename'];
      }

      if (isset($updated['tablet_name']) && !empty($updated['tablet_name'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $updated['tablet_name']);
        Storage::disk('s3')->delete($user_media_upload . $updated['tablet_name']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $updated['tablet_name'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $updated['tablet_name']);
        }
        $invalidationFiles[] = $user_media_upload . $updated['tablet_name'];
      }

      if (isset($updated['phone_name']) && !empty($updated['phone_name'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $updated['phone_name']);
        Storage::disk('s3')->delete($user_media_upload . $updated['phone_name']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $updated['phone_name'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $updated['phone_name']);
        }
        $invalidationFiles[] = $user_media_upload . $updated['phone_name'];
      }

      if (isset($updated['thumbnail']) && !empty($updated['thumbnail'])) {
        $localFile = Storage::disk('local')->get($user_media_upload . $updated['thumbnail']);
        Storage::disk('s3')->delete($user_media_upload . $updated['thumbnail']);
        if (Storage::disk('s3')->getDriver()->put($user_media_upload . $updated['thumbnail'], $localFile, $s3FileHeaders) && $deleteLocal) {
          Storage::disk('local')->delete($user_media_upload . $updated['thumbnail']);
        }
        $invalidationFiles[] = $user_media_upload . $updated['thumbnail'];
      }
    }

    if (config('atlantis.use_amazon_cdn')) {
      $syncFiles = new \Atlantis\Helpers\SyncFiles();
      $syncFiles->invalidateFiles($invalidationFiles);
    }
  }

}
