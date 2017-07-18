<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\Gallery;
use Illuminate\Support\Facades\Validator;

class GalleryRepository {

  public function validationCreate($data) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'name' => 'required'
    ];

    $messages = [
        'required' => trans('admin::validation.required')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    return $validator;
  }

  public function addGalley($data) {

    if (isset($data['imgs'])) {
      $data['images'] = implode(',', $data['imgs']);
    } else {
      $data['images'] = NULL;
    }

    $model = Gallery::create($data);

    return $model->id;
  }

  public function editGallery($id, $data) {

    $model = Gallery::find($id);

    if ($model != NULL) {

      if (isset($data['imgs'])) {
        $data['images'] = implode(',', $data['imgs']);
      } else {
        $data['images'] = NULL;
      }
      $model->update($data);
    }
  }

  public static function getAll() {
    return Gallery::all();
  }

  public static function deleteGallery($id) {

    if ($id != 1) {

      $model = Gallery::find($id);

      if ($model != NULL) {
        $model->delete();
      }
    }
  }

  public static function getGallery($id) {
    return Gallery::find($id);
  }

  public static function getAllGalleriesForSelect($empty_first = FALSE) {

    $model = Gallery::all();

    if ($empty_first) {
      $aGalleries[0] = NULl;
    } else {
      $aGalleries = array();
    }
    foreach ($model as $gallery) {
      $aGalleries[$gallery->id] = $gallery->name;
    }
    return $aGalleries;
  }

  public static function addImgToGallery($img_id, $gallery_id) {

    $model = Gallery::find($gallery_id);
    $imgModel = MediaRepository::getImage($img_id);

    if ($model != NULL && $imgModel != NULL && !empty($imgModel->thumbnail)) {
      $aImgIDs = explode(',', $model->images);

      array_push($aImgIDs, $imgModel->id);

      $aImgIDs = array_unique($aImgIDs);
      $aImgIDs = array_filter($aImgIDs);

      $model->images = implode(',', $aImgIDs);
      $model->update();

      return TRUE;
    } else {
      return FALSE;
    }
  }

  public static function getGalleriesIn($col, $values) {

    return Gallery::whereIn($col, $values)->get();
  }

}
