<?php

namespace Atlantis\Helpers\Search;

class SearchTools {

  /**
   * 
   * {!! \SearchTools::setSearchBox(['search_url']) !!}
   * <div data-pattern-func="SearchTools@setSearchBox-searchurl"></div>
   * 
   * @param type $url
   * @return mixed
   */
  public static function setSearchBox($aParams) {

    if (isset($aParams[0])) {
      $aData['url'] = $aParams[0];
    } else {
      $aData['url'] = '/';
    }
    
    $aData['search_string'] = request()->get('search');
    
    return view('atlantis::search/search-box', $aData);
  }

  /**
   * {!! \SearchTools::setResults() !!}
   * <div data-pattern-func="SearchTools@setResults"></div>
   * 
   * @return mixed
   */
  public static function setResults() {

    $search = request()->get('search');
    
    $aData = array();
    $aResults = array();

    if ($search != NULL && !empty($search)) {

      \Event::fire('search.providers', [$search]);

      $t = \App::make('Transport');

      $searchModels = $t->getEvent('search.providers', TRUE);

      //search in modules
      foreach ($searchModels as $model) {

        $aResults = array_merge($model::get($search), $aResults);
      }

      //search in pages
      $oPages = \Atlantis\Models\Repositories\PageRepository::searchInSite($search);

      foreach ($oPages as $page) {
        $aResults[$page->url] = $page->name;
      }
    }

    $aData['results'] = $aResults;
    $aData['search_string'] = $search;
    
    return view('atlantis::search/search-results', $aData);
  }

}
