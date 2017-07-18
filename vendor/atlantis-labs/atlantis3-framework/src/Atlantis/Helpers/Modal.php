<?php

namespace Atlantis\Helpers;

class Modal {

  public static function set($modal_id, $title, $body, $actionBtnName, $actionBtnHREF) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['title'] = $title;
    $aData['body'] = $body;
    $aData['actionBtnName'] = $actionBtnName;
    $aData['actionBtnHREF'] = $actionBtnHREF;

    return view('atlantis-admin::helpers/modal', $aData);
  }

  public static function setClonePage($modal_id, $formUrl, $clone_name, $clone_url) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['formUrl'] = $formUrl;
    $aData['clone_name'] = $clone_name;
    $aData['clone_url'] = $clone_url;

    return view('atlantis-admin::helpers/modal-clone-page', $aData);
  }

  public static function setClonePattern($modal_id, $formUrl, $clone_name) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['formUrl'] = $formUrl;
    $aData['clone_name'] = $clone_name;

    return view('atlantis-admin::helpers/modal-clone-pattern', $aData);
  }

  public static function removePattern($modal_id, $pattern_name, $pattern_id, $type, $oPage) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['pattern_name'] = $pattern_name;
    $aData['pattern_id'] = $pattern_id;
    $aData['type'] = $type;
    $aData['oPage'] = $oPage;

    if ($type == 'excluded') {
      $aData['title'] = 'Add Pattern';
      $aData['body'] = 'Are you sure you want to add ' . $pattern_name;
    } else {
      $aData['title'] = 'Remove Pattern';
      $aData['body'] = 'Are you sure you want to remove ' . $pattern_name;
    }

    return view('atlantis-admin::helpers/modal-remove-pattern', $aData);
  }

  public static function installModule($modal_id, $aModuleConfig) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['aModuleConfig'] = $aModuleConfig;

    return view('atlantis-admin::helpers/modal-install-module', $aData);
  }

  public static function uninstallModule($modal_id, $module_id, $module_name) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['module_id'] = $module_id;
    $aData['module_name'] = $module_name;

    return view('atlantis-admin::helpers/modal-uninstall-module', $aData);
  }

  public static function activateTheme($modal_id, $path, $theme_name) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['path'] = $path;
    $aData['theme_name'] = $theme_name;

    return view('atlantis-admin::helpers/modal-activate-theme', $aData);
  }

  public static function deactivateTheme($modal_id, $path, $theme_name) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['path'] = $path;
    $aData['theme_name'] = $theme_name;

    return view('atlantis-admin::helpers/modal-deactivate-theme', $aData);
  }

  public static function syncFiles($modal_id) {

    $aData = array();

    $aDirs = config('atlantis.s3_sync_dirs');

    if (is_array($aDirs)) {
      $aDirs = array_prepend($aDirs, config('atlantis.user_media_upload'));
    } else {
      $aDirs = [config('atlantis.user_media_upload')];
    }

    $aData['modal_id'] = $modal_id;
    $aData['dirs'] = (implode("\n", array_filter($aDirs)));
    $aData['sync_type'] = [
        'to_s3' => 'from LOCAL to S3',
        'to_local' => 'from S3 to LOCAL'
    ];

    return view('atlantis-admin::helpers/modal-sync-files', $aData);
  }

  public static function invalidateFiles($modal_id) {

    $aData = array();

    $aFiles[] = config('atlantis.user_media_upload') . ':all';
    
    if (is_array(config('atlantis.s3_sync_dirs'))) {
      foreach (config('atlantis.s3_sync_dirs') as $dir) {
        $aFiles[] = $dir . ':all';
      }
    }

    $aData['modal_id'] = $modal_id;
    $aData['inv_files'] = implode("\n", $aFiles);

    return view('atlantis-admin::helpers/modal-invalidate-files', $aData);
  }

  public static function addImgToGallery($modal_id, $img_id, $img_name, $aGalleries) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['img_id'] = $img_id;
    $aData['img_name'] = $img_name;
    $aData['galleries'] = $aGalleries;

    return view('atlantis-admin::helpers/modal-add-img-to-gallery', $aData);
  }

  public static function updateModuleModal($modal_id, $module) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['module'] = $module;

    $aVersions = array();

    foreach ($module['api']['all_versions'] as $ver) {
      if ($ver == $module['version']) {
        $aVersions['Current'][$ver] = $ver;
      } else {
        $aVersions['Available'][$ver] = $ver;
      }
    }
    $aData['aVersions'] = $aVersions;
    return view('atlantis-admin::helpers/modal-update-module', $aData);
  }

  public static function pagePreview($modal_id, $image_id = NULL) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['preview_thumb_id'] = $image_id;

    return view('atlantis-admin::helpers/modal-page-preview-image', $aData);
  }

  public static function downloadAndInstall($modal_id, $data) {

    $aData = array();

    $aData['modal_id'] = $modal_id;
    $aData['module'] = $data;

    return view('atlantis-admin::helpers/modal-download-install-module', $aData);
  }

}
