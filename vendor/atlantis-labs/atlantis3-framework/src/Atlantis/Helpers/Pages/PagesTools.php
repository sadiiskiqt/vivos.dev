<?php

namespace Atlantis\Helpers\Pages;

use Atlantis\Models\Repositories\PageRepository;

class PagesTools {

  public static function getRelatedById($page_id, $random = FALSE, $limit = NULL) {

    return PageRepository::getRelatedById($page_id, $random, $limit);
  }

  public static function getRelatedByTag($tags, $random = FALSE, $limit = NULL) {

    $_page = view()->shared('_page');

    $excluded_id = $_page != NULL ? $_page->id : 0;

    return PageRepository::getRelatedByTag($tags, $excluded_id, $random, $limit);
  }

  /**
   * {!! PagesTools::setBreadcrumbs($_page) !!}
   * 
   * @param int $page_id
   * @param \Illuminate\Support\Collection $pages
   * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public static function setBreadcrumbs($page, \Illuminate\Support\Collection $pages = NULL) {

    if ($pages === NULL) {
      $pages = PageRepository::getPagesByPath($page->path);
    } else {
      $pages = $pages->merge(PageRepository::getPagesByPath($page->path));
    }

    $last_page = $pages->last();

    if (empty($last_page)) {
      $parent = FALSE;
    } else {
      $parent = PageRepository::buildParents($last_page->id, $last_page->path);
    }


    if ($parent !== FALSE) {
      return self::setBreadcrumbs($last_page, $pages);
    }


    $data['pages'] = $pages->reverse()->unique();
    $data['current_url'] = url(request()->path());

    return view('atlantis-admin::helpers/breadcrumbs', $data);
  }

}
