<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Models\Repositories\PageRepository;
use Atlantis\Models\Repositories\PatternRepository;
use Atlantis\Models\Repositories\MediaRepository;

class DashboardController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_DASHBOARD);
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

    $search = request()->get('search');

    $aData['search_pages'] = array();
    $aData['search_patterns'] = array();
    $aData['search_media'] = array();

    if ($search != NULL) {

      $pages = PageRepository::search($search);
      $pattern = PatternRepository::search($search);
      $media = MediaRepository::search($search);
      
      $aData['search_pages'] = $pages->toArray();
      $aData['search_patterns'] = $pattern->toArray();
      $aData['search_media'] = $media->toArray();
    }

    $aData['search'] = $search;

    $lPages = PageRepository::latestEdited(6);
    $lPatterns = PatternRepository::latestEditedPatterns(6);
    $lMedia = MediaRepository::latestEdited(6);

    $latest = array();

    foreach ($lPages as $lpage) {
      $timestamp = $this->existTimestamp($lpage->updated_at, $latest);

      $latest[$timestamp]['type'] = 'page';
      $latest[$timestamp]['name'] = $lpage->name;
      $latest[$timestamp]['id'] = $lpage->id;
      if ($lpage->url == '/') {
        $latest[$timestamp]['url'] = '';
      } else {
      $latest[$timestamp]['url'] = $lpage->url;
      }
      $latest[$timestamp]['edit_url'] = '/admin/pages/edit/' . $lpage->id;
    }

    foreach ($lPatterns as $lpatt) {
      $timestamp = $this->existTimestamp($lpatt->updated_at, $latest);

      $latest[$timestamp]['type'] = 'pattern';
      $latest[$timestamp]['name'] = $lpatt->name;
      $latest[$timestamp]['id'] = $lpatt->id;
      $latest[$timestamp]['edit_url'] = '/admin/patterns/edit/' . $lpatt->id;
    }

    foreach ($lMedia as $lmed) {
      $timestamp = $this->existTimestamp($lmed->updated_at, $latest);

      $latest[$timestamp]['type'] = 'media';
      $latest[$timestamp]['name'] = $lmed->original_filename;
      $latest[$timestamp]['id'] = $lmed->id;
      $latest[$timestamp]['edit_url'] = '/admin/media/media-edit/' . $lmed->id;
    }

    krsort($latest);
   
    $aData['latest'] = $latest;
    $aData['latestPages'] = $lPages->toArray();
    $aData['latestPatterns'] = $lPatterns->toArray();
    $aData['latestMedia'] = $lMedia->toArray();
    
    $widgetBuilder = new \Atlantis\Widgets\Builder();
    $aData['widgets'] = $widgetBuilder->getWidgets();
    
    return view('atlantis-admin::dashboard', $aData);
  }

  private function existTimestamp(\Carbon\Carbon $date, $latest) {

    $timestamp = $date->toDateTimeString();

    if (isset($latest[$timestamp])) {
      $date->addSecond();
      return $this->existTimestamp($date, $latest);
    } else {
      return $timestamp;
    }
  }

}
