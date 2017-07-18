<?php

namespace Atlantis\Helpers\Media;

use Atlantis\Models\Repositories\MediaRepository;
use Atlantis\Models\Repositories\GalleryRepository;

class MediaTools {

    public static function getImagesByGallery($id) {

        $oImages = MediaRepository::getImagesByGallery($id);

        $aImages = array();

        if ($oImages != NULL && !empty($oImages)) {


            $filePath = \Atlantis\Helpers\Tools::getFilePath();

            foreach ($oImages as $image) {

                if (empty($image->filename)) {
                    $image->filename = $image->original_filename;
                    $image->name = $image->original_filename;
                }

                $image->desktop_name = $filePath . $image->original_filename;

                $image->original_filename = $filePath . $image->original_filename;

                if (!empty($image->tablet_name)) {
                    $image->tablet_name = $filePath . $image->tablet_name;
                }

                if (!empty($image->phone_name)) {
                    $image->phone_name = $filePath . $image->phone_name;
                }

                if (!empty($image->thumbnail)) {
                    $image->thumbnail = $filePath . $image->thumbnail;
                }

                $aImages[] = $image;
            }
        }
        return $aImages;

    }

    public static function getGalleries() {
        return GalleryRepository::getAll();

    }

    public static function createGallerySelector($selected_gallery = 0) {

        $data = array();

        $data['aGalleriesSelect'] = GalleryRepository::getAllGalleriesForSelect(TRUE);
        $data['selected_gallery'] = $selected_gallery;

        return view('atlantis-admin::helpers/gallery-selector', $data);

    }

    public static function createImageSelector($resize_option = NULL, $multi_images = FALSE, $image_ids = array()) {

        $data = array();

        $aStatic = array_keys(config('atlantis.static_images'));
        $aResponsive = array_keys(config('atlantis.responsive_images'));

        $aRes = array_merge($aStatic, $aResponsive);

        $aResize[NULL] = 'Do Nothing';

        foreach ($aRes as $r) {
            $aResize[$r] = $r;
        }

        $data['images'] = array();

        foreach ($image_ids as $i_id) {
            $data['images'][] = self::getImage($i_id);
        }

        $data['multi_images'] = $multi_images;
        $data['aResize'] = $aResize;
        $data['rand_id'] = mt_rand(0, 999);
        $data['resize_option'] = $resize_option;

        return view('atlantis-admin::helpers/image-selector', $data);

    }

    public static function getFeaturedImages($objects, $gallery_id_col = 'gallery_id', $object_id_col = 'id') {

        if ($objects != NULL && !$objects->isEmpty()) {

            $aGall_ids = array();
            //$aNotResult = array();
            foreach ($objects as $obj) {
                $aGall_ids[$obj->$object_id_col] = $obj->$gallery_id_col;
                //$aNotResult[$obj->$object_id_col] = NULL;
            }

            $oGalleries = GalleryRepository::getGalleriesIn('id', $aGall_ids);

            $aImg_ids = array();

            foreach ($oGalleries as $gall) {

                foreach ($aGall_ids as $blog_id => $gall_id) {

                    if ($gall_id == $gall->id) {
                        $aImgs = array_values(explode(',', $gall->images));
                        if (isset($aImgs[0])) {
                            $aImg_ids[$blog_id] = $aImgs[0];
                        } else {
                            $aImg_ids[$blog_id] = NULL;
                        }
                    }
                    /**
                      if (!isset($aImg_ids[$blog_id])) {
                      $aImg_ids[$blog_id] = NULL;
                      }
                     * 
                     */
                }
            }

            $oImages = MediaRepository::getImagesWhereIn('id', $aImg_ids)->toArray();

            $aData = array();

            $filePath = \Atlantis\Helpers\Tools::getFilePath();

            foreach ($oImages as $k => $img) {

                foreach ($aImg_ids as $blog_id => $img_id) {

                    if ($img_id == $img['id']) {

                        if (empty($img['filename'])) {
                            $oImages[$k]['filename'] = $img['original_filename'];
                        }

                        $oImages[$k]['desktop_name'] = $filePath . $img['original_filename'];

                        $oImages[$k]['original_filename'] = $filePath . $img['original_filename'];

                        if (!empty($img['tablet_name'])) {
                            $oImages[$k]['tablet_name'] = $filePath . $img['tablet_name'];
                        }

                        if (!empty($img['phone_name'])) {
                            $oImages[$k]['phone_name'] = $filePath . $img['phone_name'];
                        }

                        if (!empty($img['thumbnail'])) {
                            $oImages[$k]['thumbnail'] = $filePath . $img['thumbnail'];
                        }

                        $aData[$blog_id] = $oImages[$k];
                    }

                    /**
                      if (!isset($aData[$blog_id])) {
                      $aData[$blog_id] = NULL;
                      }
                     * 
                     */
                }
            }
            return $aData;
            /**
              if (empty($aData)) {
              return $aNotResult;
              } else {
              return $aData;
              }
             * 
             */
        } else {
            return FALSE;
        }
    }

    public static function getImage($id) {

        $media = MediaRepository::getImage($id);

        if (empty($media)) {
            return NULL;
        }
        
        return self::setValidPath($media);

    }

    public static function findByName($filename) {

        $media = MediaRepository::findByName($filename);

        if (empty($media)) {
            return NULL;
        }
        
        return self::setValidPath($media);
    }

    /**
     * 
     * @param \Atlantis\Models\Media $media
     * @return boolean|\Atlantis\Models\Media
     */
    public static function setValidPath(\Atlantis\Models\Media $media) {

        if ($media == NULL) {
            return FALSE;
        }

        $filePath = \Atlantis\Helpers\Tools::getFilePath();

        if (empty($media->filename)) {
            $media->filename = $media->original_filename;
        }

        $media->desktop_name = $filePath . $media->original_filename;

        $media->original_filename = $filePath . $media->original_filename;

        if (!empty($media->tablet_name)) {
            $media->tablet_name = $filePath . $media->tablet_name;
        }

        if (!empty($media->phone_name)) {
            $media->phone_name = $filePath . $media->phone_name;
        }

        if (!empty($media->thumbnail)) {
            $media->thumbnail = $filePath . $media->thumbnail;
        }

        return $media;

    }

}
