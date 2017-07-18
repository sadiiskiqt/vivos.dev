<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Atlantis\Models\Repositories\MediaRepository;
use Atlantis\Models\Repositories\GalleryRepository;

class MediaController extends AdminController {

  private $filePath;

  public function __construct() {
    parent::__construct(self::$_ID_MEDIA);

    $this->filePath = \Atlantis\Helpers\Tools::getFilePath();
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

    return view('atlantis-admin::media', $aData);
  }

  public function getMediaAdd() {

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

    $aData['aResize'] = $this->getResize();

    return view('atlantis-admin::media-add', $aData);
  }

  public function getMediaEdit($id = NULL) {

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

    $model = MediaRepository::getImage($id);

    if ($model != NULL) {
      
      $aData['media'] = $model;

      $tags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID(AdminController::$_ID_MEDIA, $model->id);
      $aTags = array();
      foreach ($tags as $tag) {
        $aTags[] = $tag->tag;
      }

      $aData['tags'] = implode(',', $aTags);

      $aData['filePath'] = $this->filePath;

      $aData['aResize'] = $this->getResize();
      
      if (empty($model->resize)) {
        $aData['selected_resize'] = NULL;
      } else {
        $aData['selected_resize'] = last(array_keys($model->resize));
      }
      
    } else {
      $aData['invalid_item'] = 'This file is not valid';
    }

    return view('atlantis-admin::media-edit', $aData);
  }

  public function postMediaAdd(Request $request) {

    $reciver = new \Atlantis\Helpers\PLUploadReceiver();
    $filename = $reciver->upload();

    if ($filename != NULL) {

      $data = $request->all();

      $model = MediaRepository::addMedia($data, $filename);

      if (empty($model->thumbnail)) {
        $thumbnail_path = '';
      } else {
        $thumbnail_path = \Atlantis\Helpers\Tools::getFilePath() . $model->thumbnail;
      }
      
      \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
      
      // Return Success JSON-RPC response
      return response()->json([
                  'jsonrpc' => '2.0',
                  'target_name' => $filename,
                  'id' => $model->id,
                  'thumbnail_path' => $thumbnail_path
      ]);
    }
  }

  public function postMediaEdit($id = NULL, Request $request) {

    $data = $request->all();

    $model = MediaRepository::getImage($id);

    if (empty($request->file())) {
      //dd('without new file', $request->all());

      MediaRepository::editMedia($id, $data);

      \Session::flash('success', 'File was updated');

      \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
      
      if ($request->get('_update')) {
        return redirect('admin/media/media-edit/' . $id);
      } else {
        return redirect('admin/media');
      }
    } else {

      $reciver = new \Atlantis\Helpers\PLUploadReceiver();
      $filename = $reciver->upload();

      \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
      
      if ($filename != NULL && $model != NULL) {

        // Return Success JSON-RPC response
       return MediaRepository::editMediaWithFile($id, $data, $filename);       
        
      }
    }
  }

  public function postBulkActionMedia(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          MediaRepository::deleteMedia($id);
        }
        \Session::flash('success', 'Files was deleted');
      }
    }

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    
    return redirect()->back();
  }

  public function getMediaDelete($id = NULL) {

    MediaRepository::deleteMedia($id);

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();
    
    \Session::flash('success', 'File was deleted');
    return redirect()->back();
  }

  private function getResize() {

    $aStatic = array_keys(config('atlantis.static_images'));
    $aResponsive = array_keys(config('atlantis.responsive_images'));
   
    $aRes = array_merge($aStatic, $aResponsive);

    $aResize[NULL] = 'Do Nothing';

    foreach ($aRes as $r) {
      $aResize[$r] = $r;
    }

    return $aResize;
  }

  /**
   * **********************************************
   * Galleries
   */
  public function getGalleryAdd() {

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

    return view('atlantis-admin::gallery-add', $aData);
  }

  public function getGalleryEdit($id = NULL) {

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

    $model = GalleryRepository::getGallery($id);

    if ($model != NULL) {
      $aData['gallery'] = $model;

      $images = array_filter(explode(',', $model->images));
      $aImages = array();

      foreach ($images as $k => $image_id) {
        $oImg = MediaRepository::getImage($image_id);

        if ($oImg != NULL && !empty($oImg->thumbnail)) {
          $aImages[$k]['src'] = \Atlantis\Helpers\Tools::getFilePath() . config('media_user_upload') . $oImg->thumbnail;
          $aImages[$k]['id'] = $oImg->id;
        }
      }

      $aData['aResize'] = $this->getResize();
      $aData['aImages'] = $aImages;
    } else {
      $aData['invalid_item'] = 'This gallery is not valid';
    }

    return view('atlantis-admin::gallery-edit', $aData);
  }

  public function postGalleryAdd(Request $request) {

    $model = new GalleryRepository();

    $data = $request->all();

    $validator = $model->validationCreate($data);

    if (!$validator->fails()) {

      $id = $model->addGalley($data);

      \Session::flash('success', 'Gallery ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/media/gallery-edit/' . $id);
      } else {
        \Session::flash('tab_panel', 'gallery');
        return redirect('admin/media');
      }
    } else {

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function postGalleryEdit($id = NULL, Request $request) {

    $model = new GalleryRepository();

    $data = $request->all();

    $validator = $model->validationCreate($data);

    if (!$validator->fails()) {

      $model->editGallery($id, $data);

      \Session::flash('success', 'Gallery ' . $data['name'] . ' was updated');

      if ($request->get('_update')) {
        return redirect('admin/media/gallery-edit/' . $id);
      } else {
        \Session::flash('tab_panel', 'gallery');
        return redirect('admin/media');
      }
    } else {

      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function getGalleryDelete($id = NULL) {

    GalleryRepository::deleteGallery($id);

    \Session::flash('success', 'Gallery was deleted');
    \Session::flash('tab_panel', 'gallery');
    return redirect()->back();
  }

  public function postBulkActionGallery(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          GalleryRepository::deleteGallery($id);
        }
        \Session::flash('success', 'Galleries was deleted');
      }
    }

    \Session::flash('tab_panel', 'gallery');
    return redirect()->back();
  }

  public function postAddImgToGallery($id = NULL, Request $request) {

    GalleryRepository::addImgToGallery($id, $request->get('gallery'));

    \Session::flash('success', 'Image was added to gallery');
    return redirect()->back();
  }

  public function postAddToGallery(Request $request) {

    $reciver = new \Atlantis\Helpers\PLUploadReceiver();
    $filename = $reciver->upload();

    if ($filename != NULL) {

      $data = $request->all();

      $data['filename'] = NULL;
      $data['caption'] = NULL;
      $data['credit'] = NULL;
      $data['description'] = NULL;
      $data['alt'] = NULL;
      $data['weight'] = 1;
      $data['css'] = NULL;
      $data['anchor_link'] = NULL;
      $data['tags'] = NULL;

      $model = MediaRepository::addMedia($data, $filename);

      //GalleryRepository::addImgToGallery($model->id, $data['gallery_id']);

      $success = GalleryRepository::addImgToGallery($model->id, $data['gallery_id']);

      if ($success) {
        return response()->json([
                    'jsonrpc' => 2.0,
                    'success' => [
                        'image_id' => $model->id,
                        'thumbnail' => \Atlantis\Helpers\Tools::getFilePath() . $model->thumbnail
                    ]
        ]);
      } else {
        MediaRepository::deleteMedia($model->id);
        return response()->json([
                    'jsonrpc' => 2.0,
                    'error' => 'Invalid gallery id or imgae file'
        ]);
      }
    }
  }

  public function anyAllGalleries(Request $request) {

    $oGalleries = GalleryRepository::getAll();

    $aData = array();

    foreach ($oGalleries as $k => $gallery) {
      $aData[$k]['id'] = $gallery->id;
      $aData[$k]['name'] = $gallery->name;
    }

    return response()->json($aData);
  }

}
