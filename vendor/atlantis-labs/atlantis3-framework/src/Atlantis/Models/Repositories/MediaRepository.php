<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Media;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Atlantis\Models\Repositories\TagRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MediaRepository {

  public static function getAll() {
    return Media::all();
  }

  public static function getImage($id) {

    return Media::find($id);
  }

  public static function search($search) {

    return Media::where('id', 'LIKE', '%' . $search . '%')
                    ->orWhere('original_filename', 'LIKE', '%' . $search . '%')
                    ->orWhere('original_filename', 'LIKE', '%' . $search . '%')
                    ->orWhere('caption', 'LIKE', '%' . $search . '%')
                    ->orWhere('credit', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%')
                    ->orWhere('type', 'LIKE', '%' . $search . '%')
                    ->orWhere('alt', 'LIKE', '%' . $search . '%')
                    ->orWhere('tablet_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('phone_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('thumbnail', 'LIKE', '%' . $search . '%')
                    ->get();
  }

  public static function latestEdited($limit) {

    return Media::take($limit)
                    ->orderBy('updated_at', 'DESC')
                    ->get();
  }

  public static function addMedia($data, $filename) {

    $filePath = config('atlantis.user_media_upload') . $filename;
    $filename_desktop = '';
    $filename_tablet = '';
    $filename_phone = '';
    $filename_thumbnail = '';
    $mimeType = File::mimeType($filePath);

    if (getimagesize($filePath) === FALSE) {
      //save file
    } else {

      //save or resize image
      if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.responsive_images'))) {
        //resize responsive image
        $aResize = config('atlantis.responsive_images')[$data['resize']];

        $desktopCrop = isset($aResize['desktop']['crop']) ? $aResize['desktop']['crop'] : FALSE;
        $tabletCrop = isset($aResize['tablet']['crop']) ? $aResize['tablet']['crop'] : FALSE;
        $phoneCrop = isset($aResize['phone']['crop']) ? $aResize['phone']['crop'] : FALSE;
        $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

        $filename_desktop = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_desktop', $aResize['desktop']['width'], $aResize['desktop']['height'], $desktopCrop);
        $filename_tablet = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_tablet', $aResize['tablet']['width'], $aResize['tablet']['height'], $tabletCrop);
        $filename_phone = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_phone', $aResize['phone']['width'], $aResize['phone']['height'], $phoneCrop);
        $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop);

        unlink($filePath);
      } else if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.static_images'))) {
        //resize static image
        $aResize = config('atlantis.static_images')[$data['resize']];

        $fullsizeCrop = isset($aResize['fullsize']['crop']) ? $aResize['fullsize']['crop'] : FALSE;
        $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

        $filename_desktop = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_desktop', $aResize['fullsize']['width'], $aResize['fullsize']['height'], $fullsizeCrop);
        $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop);

        unlink($filePath);
      } else {
        //save image
        $filename_thumbnail = self::resizeImage(config('atlantis.user_media_upload'), $filename, '_thumbnail', 200, 150, TRUE);
      }
    }

    if (!empty($filename_desktop)) {
      $filename = $filename_desktop;
    }

    $fileSize = File::size(config('atlantis.user_media_upload') . $filename);

    if (!empty($data['resize'])) {
      $resize[$data['resize']] = isset($aResize) ? $aResize : array();
    } else {
      $resize = NULL;
    }

    $newData = [
        'filename' => $data['filename'],
        'original_filename' => $filename,
        'tablet_name' => $filename_tablet,
        'phone_name' => $filename_phone,
        'thumbnail' => $filename_thumbnail,
        'filesize' => $fileSize,
        'type' => $mimeType,
        'caption' => $data['caption'],
        'credit' => $data['credit'],
        'description' => $data['description'],
        'alt' => $data['alt'],
        'weight' => $data['weight'],
        'css' => $data['css'],
        'anchor_link' => $data['anchor_link'],
        'resize' => serialize($resize)
    ];

    $model = Media::create($newData);

    TagRepository::addTagsWithDelimiter(',', $data['tags'], $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);

    $eventData = [
        'added' => [
            'original_filename' => $filename,
            'tablet_name' => $filename_tablet,
            'phone_name' => $filename_phone,
            'thumbnail' => $filename_thumbnail
        ]
    ];

    /** Fire the file.uploaded event * */
    \Event::fire('file.uploaded', [$eventData]);

    self::updateFileAfterEvents($model);

    return $model;
  }

  public static function editMediaWithFile($id, $data, $filename) {

    $model = Media::find($id);

    $user_media_upload = config('atlantis.user_media_upload');

    $filePath = $user_media_upload . $filename;

    if ($model != NULL) {

      $filename_desktop = '';
      $filename_tablet = '';
      $filename_phone = '';
      $filename_thumbnail = '';
      $mimeType = File::mimeType($filePath);

      $ext = pathinfo($filePath, PATHINFO_EXTENSION);
      $existExt = pathinfo($user_media_upload . $model->original_filename, PATHINFO_EXTENSION);

      if ($mimeType != $model->type || $ext != $existExt) {

        unlink($filePath);

        return response()->json([
                    'jsonrpc' => 2.0,
                    'error' => 'The file extension is not the same.'
        ]);
      }

      if (getimagesize($filePath) === FALSE) {
        //save file
      } else {

        //save or resize image
        if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.responsive_images'))) {
          //resize responsive image
          $aResize = config('atlantis.responsive_images')[$data['resize']];

          $desktopCrop = isset($aResize['desktop']['crop']) ? $aResize['desktop']['crop'] : FALSE;
          $tabletCrop = isset($aResize['tablet']['crop']) ? $aResize['tablet']['crop'] : FALSE;
          $phoneCrop = isset($aResize['phone']['crop']) ? $aResize['phone']['crop'] : FALSE;
          $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

          $filename_desktop = self::resizeImage($user_media_upload, $filename, '_desktop', $aResize['desktop']['width'], $aResize['desktop']['height'], $desktopCrop);
          $filename_tablet = self::resizeImage($user_media_upload, $filename, '_tablet', $aResize['tablet']['width'], $aResize['tablet']['height'], $tabletCrop);
          $filename_phone = self::resizeImage($user_media_upload, $filename, '_phone', $aResize['phone']['width'], $aResize['phone']['height'], $phoneCrop);
          $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop);

          unlink($filePath);
        } else if (isset($data['resize']) && array_key_exists($data['resize'], config('atlantis.static_images'))) {
          //resize static image
          $aResize = config('atlantis.static_images')[$data['resize']];

          $fullsizeCrop = isset($aResize['fullsize']['crop']) ? $aResize['fullsize']['crop'] : FALSE;
          $thumbnailCrop = isset($aResize['thumbnail']['crop']) ? $aResize['thumbnail']['crop'] : FALSE;

          $filename_desktop = self::resizeImage($user_media_upload, $filename, '_desktop', $aResize['fullsize']['width'], $aResize['fullsize']['height'], $fullsizeCrop);
          $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', $aResize['thumbnail']['width'], $aResize['thumbnail']['height'], $thumbnailCrop);

          unlink($filePath);
        } else {
          //save image
          $filename_thumbnail = self::resizeImage($user_media_upload, $filename, '_thumbnail', 200, 150, TRUE);
        }
      }

      if (!empty($filename_desktop)) {
        $filename = $filename_desktop;
      }

      $fileSize = File::size($user_media_upload . $filename);

      $eventData = array();

      if (!empty($model->original_filename)) {
        if (Storage::has($user_media_upload . $model->original_filename)) {
          \Storage::delete($user_media_upload . $model->original_filename);
        }
        if (!empty($filename)) {
          \Storage::move($user_media_upload . $filename, $user_media_upload . $model->original_filename);
          $filename = $model->original_filename;
          $eventData['updated']['original_filename'] = $filename;
        } else {
          $eventData['removed']['original_filename'] = $model->original_filename;
        }
      } else {
        $eventData['added']['original_filename'] = $filename;
      }

      if (!empty($model->tablet_name)) {
        if (Storage::has($user_media_upload . $model->tablet_name)) {
          \Storage::delete($user_media_upload . $model->tablet_name);
        }
        if (!empty($filename_tablet)) {
          \Storage::move($user_media_upload . $filename_tablet, $user_media_upload . $model->tablet_name);
          $filename_tablet = $model->tablet_name;
          $eventData['updated']['tablet_name'] = $filename_tablet;
        } else {
          $eventData['removed']['tablet_name'] = $model->tablet_name;
        }
      } else {
        $eventData['added']['tablet_name'] = $filename_tablet;
      }

      if (!empty($model->phone_name)) {
        if (Storage::has($user_media_upload . $model->phone_name)) {
          \Storage::delete($user_media_upload . $model->phone_name);
        }
        if (!empty($filename_phone)) {
          \Storage::move($user_media_upload . $filename_phone, $user_media_upload . $model->phone_name);
          $filename_phone = $model->phone_name;
          $eventData['updated']['phone_name'] = $filename_phone;
        } else {
          $eventData['removed']['phone_name'] = $model->phone_name;
        }
      } else {
        $eventData['added']['phone_name'] = $filename_phone;
      }

      if (!empty($model->thumbnail)) {
        if (Storage::has($user_media_upload . $model->thumbnail)) {
          \Storage::delete($user_media_upload . $model->thumbnail);
        }
        if (!empty($filename_thumbnail)) {
          \Storage::move($user_media_upload . $filename_thumbnail, $user_media_upload . $model->thumbnail);
          $filename_thumbnail = $model->thumbnail;
          $eventData['updated']['thumbnail'] = $filename_thumbnail;
        } else {
          $eventData['removed']['thumbnail'] = $model->thumbnail;
        }
      } else {
        $eventData['added']['thumbnail'] = $filename_thumbnail;
      }

      if (!empty($data['resize'])) {
        $resize[$data['resize']] = isset($aResize) ? $aResize : array();
      } else {
        $resize = NULL;
      }

      $newData = [
          'filename' => $data['filename'],
          'original_filename' => $filename,
          'tablet_name' => $filename_tablet,
          'phone_name' => $filename_phone,
          'thumbnail' => $filename_thumbnail,
          'filesize' => $fileSize,
          'type' => $mimeType,
          'caption' => $data['caption'],
          'credit' => $data['credit'],
          'description' => $data['description'],
          'alt' => $data['alt'],
          'weight' => $data['weight'],
          'css' => $data['css'],
          'anchor_link' => $data['anchor_link'],
          'resize' => serialize($resize)
      ];

      $model->update($newData);
      TagRepository::updateTagsWithDelimiter(',', $data['tags'], $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);

      /** Fire the file.uploaded event * */
      \Event::fire('file.uploaded', [$eventData]);

      self::updateFileAfterEvents($model);

      return response()->json([
                  'jsonrpc' => '2.0',
                  'target_name' => $filename,
                  'id' => $id
      ]);
    } else {
      unlink($filePath);

      return response()->json([
                  'jsonrpc' => 2.0,
                  'error' => 'Oops something wrong.'
      ]);
    }
  }

  private static function updateFileAfterEvents($model) {
    $mediaPath = config('atlantis.user_media_upload');
    if (config('atlantis.use_amazon_s3') && config('atlantis.delete_local_file')) {
      $size = Storage::disk('s3')->size($mediaPath . $model->original_filename);
      $mimeType = $model->type;
    } else {

      $size = Storage::disk('local')->size($mediaPath . $model->original_filename);
      $mimeType = File::mimeType($mediaPath . $model->original_filename);
    }

    if ($model->filesize != $size || $model->type != $mimeType) {

      $model->update([
          'filesize' => $size,
          'type' => $mimeType
      ]);
    }
  }

  public static function editMedia($id, $data) {

    $model = Media::find($id);

    if ($model != NULL) {

      $newData = [
          'filename' => $data['filename'],
          'caption' => $data['caption'],
          'credit' => $data['credit'],
          'description' => $data['description'],
          'alt' => $data['alt'],
          'weight' => $data['weight'],
          'css' => $data['css'],
          'anchor_link' => $data['anchor_link']
      ];

      $model->update($newData);

      TagRepository::updateTagsWithDelimiter(',', $data['tags'], $model->id, \Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA);
    }
  }

  /**
   * 
   * resize and save new image
   * 
   * @param type $uploadPath
   * @param type $filename
   * @param type $sufix
   * @param type $width
   * @param type $height
   * @return string
   */
  public static function resizeImage($uploadPath, $filename, $sufix, $width, $height, $crop = FALSE) {

    $imageSize = getimagesize($uploadPath . $filename);

    $fileSizeWidth = $imageSize[0];
    $fileSizeHeight = $imageSize[1];

    $name = pathinfo($uploadPath . $filename, PATHINFO_FILENAME);
    $ext = pathinfo($uploadPath . $filename, PATHINFO_EXTENSION);

    $filenameWithSufix = $name . $sufix . '.' . $ext;

    $file = Image::make($uploadPath . $filename);

    if (($width / $height) > ($fileSizeWidth / $fileSizeHeight)) {
      $file->widen($width);
    } else if (($width / $height) < ($fileSizeWidth / $fileSizeHeight)) {
      $file->heighten($height);
    } else {
      $file->resize($width, $height);
    }

    if ($crop) {
      $file->crop($width, $height);
    }

    $file->save($uploadPath . $filenameWithSufix);

    return $filenameWithSufix;
  }

  public static function deleteMedia($id) {

    $model = Media::find($id);

    if ($model != NULL) {

      $user_media_upload = config('atlantis.user_media_upload');

      $old_original_filename = $model->original_filename;
      $old_tablet_name = $model->tablet_name;
      $old_phone_name = $model->phone_name;
      $old_thumbnail = $model->thumbnail;

      $original_file_path = $user_media_upload . $old_original_filename;
      $tablet_file_path = $user_media_upload . $old_tablet_name;
      $phone_file_path = $user_media_upload . $old_phone_name;
      $thumb_file_path = $user_media_upload . $old_thumbnail;

      $model->delete();
      TagRepository::deleteTag(\Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA, $id);

      //delete local files 
      if (!empty($old_original_filename) && Storage::disk('local')->has($original_file_path)) {
        Storage::disk('local')->delete($original_file_path);
      }
      if (!empty($old_tablet_name) && Storage::disk('local')->has($tablet_file_path)) {
        Storage::disk('local')->delete($tablet_file_path);
      }
      if (!empty($old_phone_name) && Storage::disk('local')->has($phone_file_path)) {
        Storage::disk('local')->delete($phone_file_path);
      }
      if (!empty($old_thumbnail) && Storage::disk('local')->has($thumb_file_path)) {
        Storage::disk('local')->delete($thumb_file_path);
      }

      if (config('atlantis.use_amazon_s3')) {
        //delete amazon s3 files
        if (!empty($old_original_filename)) {
          Storage::disk('s3')->delete($original_file_path);
        }
        if (!empty($old_tablet_name)) {
          Storage::disk('s3')->delete($tablet_file_path);
        }
        if (!empty($old_phone_name)) {
          Storage::disk('s3')->delete($phone_file_path);
        }
        if (!empty($old_thumbnail)) {
          Storage::disk('s3')->delete($thumb_file_path);
        }
      }
    }
  }

  public static function getImagesByGallery($gallery_id) {

    $gallery = GalleryRepository::getGallery($gallery_id);

    if ($gallery != NULL) {

      $aImgsIds = array_filter(explode(',', $gallery->images));

      //$oImages = Media::whereIn('id', $aImgsIds)->orderByRaw("FIND_IN_SET('id','$gallery->images')")->get();

      if (empty($gallery->images)) {
        return array();
      }
      
      $oImages = DB::select(DB::raw("select * from media where id in ($gallery->images) order by find_in_set(id, '$gallery->images')"));

      return $oImages;
    } else {
      return NULL;
    }
  }

  public static function getImagesWhereIn($col, $values) {

    return Media::whereIn($col, $values)->get();
  }
  
  /**
   * 
   * @param string $filename
   * @return Media
   */
  public static function findByName($filename) {
      
     return \Atlantis\Helpers\Cache\AtlantisCache::rememberQuery('mediaFindByName', [$filename], function() use ($filename) {

              return Media::where('filename', '=', $filename)
              ->orWhere('original_filename', '=', $filename)
              ->orWhere('tablet_name', '=', $filename)
              ->orWhere('phone_name', '=', $filename)
              ->first();
            });
      
      
      
  }

}
