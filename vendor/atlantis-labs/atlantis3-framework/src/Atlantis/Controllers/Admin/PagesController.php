<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use \Illuminate\Http\Request;
use Atlantis\Models\Repositories\PagesCategoriesRepository;
use Atlantis\Models\Repositories\PageRepository;
use Atlantis\Helpers\Tools;

class PagesController extends AdminController {

  public static $_STATUSES = [
      2 => 'In Development',
      1 => 'Active',
      0 => 'Deactivated'
  ];

  public function __construct() {

    parent::__construct(self::$_ID_PAGES);
  }

  public function getIndex() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'][] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'][] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'][] = \Session::get('error');
    }

    return view('atlantis-admin::pages', $aData);
  }

  public function getAdd() {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'][] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'][] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'][] = \Session::get('error');
    }

    $aData['aLang'] = Tools::getThemeLanguages();

    $aData['aCategories'] = $this->getCategories();

    $aData['aTemplates'] = Tools::getTemplates();

    $aData['default_template'] = '';

    $aData['aParent'] = $this->getPagesList();

    $aData['aStatuses'] = self::$_STATUSES;

    $aData['username'] = auth()->user()->name;

    return view('atlantis-admin::pages-add', $aData);
  }

  public function postAdd(Request $request) {

    $oPages = new PageRepository();

    $data = $request->all();

    $validator = $oPages->validationCreate($data);

    if (!$validator->fails()) {

      $page_id = $oPages->createPage($data);

      \Session::flash('success', 'Page ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/pages/edit/' . $page_id);
      } else {
        return redirect('admin/pages');
      }
    } else {
      return redirect()->back()->withErrors($validator)->withInput();
    }
  }

  public function getEdit($id = NULL, $version_id = NULL, $lang = NULL) {

    $aData = array();

    if (\Session::get('info') != NULL) {
      $aData['msgInfo'][] = \Session::get('info');
    }

    if (\Session::get('success') != NULL) {
      $aData['msgSuccess'][] = \Session::get('success');
    }

    if (\Session::get('error') != NULL) {
      $aData['msgError'][] = \Session::get('error');
    }

    if ($lang == NULL) {
      $lang = config('atlantis.default_language');
    }

    if ($version_id == NULL) {
      //get page with active version
      $oPage = PageRepository::getPageWithActiveVersion($id, $lang);

      if ($oPage == NULL) {
        $oPage = PageRepository::getPageWithActiveVersion($id);
        if ($oPage != NULL) {
          $aData['msgInfo'][] = 'This page does not have a version in your default language.';
        }
      }
    } else {
      //get page by version
      $oPage = PageRepository::getPageByVersion($id, $version_id, $lang);
    }

    if ($oPage != NULL) {

      $aData['oPage'] = $oPage;

      $editorParser = new \Atlantis\Helpers\Dom\EditorParser($oPage->page_body);
      $editorParser->process();
      $aData['included_modules'] = $editorParser->withModules();

      $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);
      $aData['aPatterns'] = $this->sortPatternsByType($oPatterns, $oPage->url);

      if (empty($oPage->styles) || $oPage->styles == NULL) {
        $aData['styles_count'] = 0;
      } else {
        $aData['styles_count'] = count(explode("\n", $oPage->styles));
      }
      if (empty($oPage->scripts) || $oPage->scripts == NULL) {
        $aData['scripts_count'] = 0;
      } else {
        $aData['scripts_count'] = count(explode("\n", $oPage->scripts));
      }

      $aData['data_status'] = $this->getDataStatus($oPage);

      $oTags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID(AdminController::$_ID_PAGES, $id);

      $aTags = array();

      foreach ($oTags as $tag) {
        $aTags[] = $tag->tag;
      }

      $aData['tags'] = implode(',', $aTags);

      $parent = PageRepository::buildParents($id, $oPage->path);

      $aData['path'] = last(array_filter(explode('/', $parent)));

      $aData['aCategories'] = $this->getCategories();

      $aData['aTemplates'] = Tools::getTemplates();

      $aData['aStatuses'] = self::$_STATUSES;

      $aData['aLang'] = Tools::getThemeLanguages();

      $aData['aParent'] = $this->getPagesList();

      $aData['start_date'] = Tools::getExpirationDateForView($oPage->start_date);

      $aData['end_date'] = Tools::getExpirationDateForView($oPage->end_date);

      $aData['related_image'] = $this->getRelatedImage($oPage->preview_thumb_id);
    } else {
      $aData['invalid_item'] = 'This page is not valid';
    }

    return view('atlantis-admin::pages-edit', $aData);
  }

  public function postEdit($id = NULL, Request $request) {
    //dd($request->all());
    if ($id != NULL) {

      if ($request->get('_remove_pattern_specific')) {
        $this->removePattern($id, $request->get('_remove_pattern_specific'), 'specific');
        return redirect()->back()->withInput();
      } else if ($request->get('_remove_pattern_common')) {
        $this->removePattern($id, $request->get('_remove_pattern_common'), 'common');
        return redirect()->back()->withInput();
      } else if ($request->get('_remove_pattern_excluded')) {
        $this->removePattern($id, $request->get('_remove_pattern_excluded'), 'excluded');
        return redirect()->back()->withInput();
      }

      $oPages = new PageRepository();

      $data = $request->all();

      $validator = $oPages->validationCreate($data, $id);

      if (!$validator->fails()) {

        $oPages->updatePage($id, $data);

        \Session::flash('success', 'Page ' . $data['name'] . ' was updated');

        if ($request->get('_update')) {
          return redirect('admin/pages/edit/' . $id);
        } else {
          return redirect('admin/pages');
        }
      } else {

        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }

  /**
   * Return JSON with pattern by pageID
   * 
   * /admin/pages/patterns-per-page/{page_id}
   * 
   */
  public function anyPatternsPerPage($id = NULL) {

    $oPage = PageRepository::getPage($id);

    if ($oPage != NULL) {

      $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);

      return response()->json($this->sortPatternsByType($oPatterns, $oPage->url));
    } else {
      return response()->json([
                  'error' => "invalid page ID"
      ]);
    }
  }

  /**
   * 
   * /admin/pages/remove-pattern/{page_id}/{pattern_id}/{type}
   * 
   * @param type $page_id
   * @param type $pattern_id
   * @param type $type - specific/common/excluded
   * @return type
   */
  public function anyRemovePattern($page_id = NULL, $pattern_id = NULL, $type = NULL) {

    $aData = array();

    if ($page_id == NULL) {
      $aData['error'] = 'Add page ID';
    } else if ($pattern_id == NULL) {
      $aData['error'] = 'Add pattern ID';
    } else if ($type == NULL) {
      $aData['error'] = 'Add pattern type';
    } else {
      $aData = $this->removePattern($page_id, $pattern_id, $type);
    }

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

    return response()->json($aData);
  }

  public function removePattern($page_id, $pattern_id, $type) {

    //dd($page_id, $pattern_id, $type);

    $oPage = \Atlantis\Models\Page::find($page_id);

    if ($oPage != NULL) {

      $positive = $oPage->url;
      $negative = '!' . $oPage->url;

      if ($type == 'specific') {
        $oMask = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($pattern_id, $positive)->first();

        if ($oMask != NULL) {
          $oMask->mask = $negative;
          $oMask->update();

          $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);

          return ['success' => 'Pattern was removed from page', 'patterns' => $this->sortPatternsByType($oPatterns, $oPage->url)];
        } else {
          return ['error' => 'Invalid pattern for page'];
        }
      } else if ($type == 'common') {

        $oMaskPos = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($pattern_id, $positive)->first();
        $oMaskNeg = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($pattern_id, $negative)->first();
        //dd($oMaskPos, $oMaskNeg);
        if ($oMaskPos != NULL) {
          $oMaskPos->mask = $negative;
          $oMaskPos->update();

          $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);

          return ['success' => 'Pattern was removed from page', 'patterns' => $this->sortPatternsByType($oPatterns, $oPage->url)];
        }

        if ($oMaskPos == NULL && $oMaskNeg == NULL) {

          \Atlantis\Models\Repositories\PatternsMasksRepository::saveMask($pattern_id, $negative);

          $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);

          return ['success' => 'Pattern was removed from page', 'patterns' => $this->sortPatternsByType($oPatterns, $oPage->url)];
        }
      } else if ($type = 'excluded') {

        $oMaskNeg = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($pattern_id, $negative)->first();
        $oMaskAny = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($pattern_id, ':any')->first();

        if ($oMaskNeg != NULL) {
          if ($oMaskAny != NULL) {
            $oMaskNeg->delete();
          } else {
            $oMaskNeg->mask = $positive;
            $oMaskNeg->update();
          }
          $oPatterns = \Atlantis\Models\Repositories\PatternRepository::getAllPerPage($oPage->url);

          return ['success' => 'Pattern was added to page', 'patterns' => $this->sortPatternsByType($oPatterns, $oPage->url)];
        }
      } else {

        return ['error' => 'Invalid pattern type'];
      }
    } else {

      return ['error' => 'This pattern can not remove from the page'];
    }
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      $oPage = new PageRepository();

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          $oPage->changeStatus($id, 5);
        }
        \Session::flash('success', 'Pages was deleted');
      } else if ($request->get('action') == 'bulk_deactivate') {

        foreach ($aIDs as $id) {
          $oPage->changeStatus($id, 0);
        }
        \Session::flash('success', 'Pages was deactivated');
      } else if ($request->get('action') == 'bulk_activate') {

        foreach ($aIDs as $id) {
          $oPage->changeStatus($id, 1);
        }
        \Session::flash('success', 'Pages was activated');
      }
    }

    return redirect()->back();
  }

  public function postBulkActionVersions(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {

          $version = \Atlantis\Models\Repositories\PagesVersionsRepository::getVersion($id);

          if ($version != NULL) {
            if ($version->active != 1) {
              $version->delete();
            }
          }
        }
        \Session::flash('success', 'Versions was deleted');
      }
    }

    \Atlantis\Helpers\Cache\AtlantisCache::clearAll();

    return redirect()->back();
  }

  public function getDeletePage($page_id) {

    $prevUrl = parse_url(\Illuminate\Support\Facades\URL::previous());

    $oPage = new PageRepository();
    $oPage->changeStatus($page_id, 5);

    \Session::flash('success', 'Page was deleted');

    if (isset($prevUrl['path']) && $prevUrl['path'] == '/admin/dashboard') {

      $query = '';

      if (isset($prevUrl['query'])) {
        $query = '?' . $prevUrl['query'];
      }

      return redirect('admin/dashboard' . $query);
    } else {
      return redirect('admin/pages');
    }
  }

  public function getMakeActiveVersion($id = NULL, $version_id = NULL, $lang = NULL) {

    \Atlantis\Models\Repositories\PagesVersionsRepository::makeActiveVersion($id, $version_id, $lang);

    return redirect()->back();
  }

  public function postClonePage(Request $request, $id = NULL, $lang = NULL) {

    if ($id != NULL) {

      if ($lang == NULL) {
        $lang = config('atlantis.default_language');
      }

      $data = $request->all();

      $oPages = new PageRepository();

      $validator = $oPages->validationClone($data);

      if (!$validator->fails()) {

        $cloned = $oPages->clonePage($id, $data, auth()->user()->id, auth()->user()->name, $lang);

        if ($cloned !== FALSE) {
          \Session::flash('success', 'Page was cloned.');
          return redirect('admin/pages/edit/' . $cloned->id);
        } else {
          \Session::flash('error', 'Ooops something went wrong.');
        }
      } else {

        $msgs = implode('<br>', $validator->errors()->all());

        \Session::flash('error', $msgs);
      }
    } else {
      \Session::flash('error', 'Invalid page.');
    }

    return redirect()->back();
  }

  public function getDeleteVersion($id = NULL, $version_id = NULL, $lang = NULL) {

    \Atlantis\Models\Repositories\PagesVersionsRepository::deleteVersion($id, $version_id, $lang);

    \Session::flash('success', 'Version ' . $version_id . ' was deleted');

    return redirect()->back();
  }

  public function anyRelatedImages() {

    return response()->json(\Atlantis\Helpers\Media\MediaTools::getImagesByGallery(1));
  }

  private function getCategories() {

    $oCategories = PagesCategoriesRepository::getAll();

    $aCats[0]['category_name'] = '';
    $aCats[0]['category_action'] = '';
    $aCats[0]['category_string'] = '';
    $aCats[0]['category_view'] = '';

    foreach ($oCategories as $cat) {

      $aCats[$cat->id]['category_name'] = $cat->category_name;
      $aCats[$cat->id]['category_action'] = $cat->category_action;
      $aCats[$cat->id]['category_string'] = $cat->category_string;
      $aCats[$cat->id]['category_view'] = $cat->category_view;
    }

    return $aCats;
  }

  private function getPagesList() {

    $oPages = PageRepository::gellAll();

    $aPages[0] = '';

    foreach ($oPages as $page) {

      if ($page->status == 1) {

        $aPages[$page->id] = $page->name;
      }
    }

    return $aPages;
  }

  private function getDataStatus($oPage) {

    if ($oPage->status == 0) {
      $status = 'disabled';
    } else if ($oPage->status == 1) {
      $status = 'active';
    } else if ($oPage->status == 2) {
      $status = 'dev';
    } else if ($oPage->status == 5) {
      $status = 'disabled';
    } else {
      $status = 'disabled';
    }

    return $status;
  }

private function sortPatternsByType($oPatterns, $url) {

    $aPatterns = array();
    $aPatterns['count'] = count($oPatterns);
    
    $s = 0;
    $e = 0;
    $c = 0;    
    foreach ($oPatterns as $patt) {
      

    //page specific
    $sp = str_replace(':any', '', $patt->mask);
    if ($patt->mask == $url || (!empty($sp) && starts_with($url, $sp))) {
      $aPatterns['specific'][$s]['id'] = $patt->id;
      $aPatterns['specific'][$s]['name'] = $patt->name;
      $aPatterns['specific'][$s]['mask_id'] = $patt->mask_id;
      $aPatterns['specific'][$s]['status'] = $patt->status;
      $s++;
    }

      //excluded
      $ex = str_replace(':all', '', $patt->mask);
      if ($patt->mask == '!' . $url || (!empty($ex) && starts_with('!' . $url, $ex))) {
        $aPatterns['excluded'][$e]['id'] = $patt->id;
        $aPatterns['excluded'][$e]['name'] = $patt->name;
        $aPatterns['excluded'][$e]['mask_id'] = $patt->mask_id;
        $aPatterns['excluded'][$e]['status'] = $patt->status;
        $e++;
      }

      //common
      if ($patt->mask == ':any') {
        $aPatterns['common'][$c]['id'] = $patt->id;
        $aPatterns['common'][$c]['name'] = $patt->name;
        $aPatterns['common'][$c]['mask_id'] = $patt->mask_id;
        $aPatterns['common'][$c]['status'] = $patt->status;
        $c++;
      }
    }

    return $aPatterns;
  }

  private function getRelatedImage($image_id) {

    $oMedia = \Atlantis\Models\Repositories\MediaRepository::getImage($image_id);

    if ($oMedia != NULL && !empty($oMedia->thumbnail)) {

      $filePath = Tools::getFilePath();

      if (empty($oMedia->filename)) {
        $oMedia->filename = $oMedia->original_filename;
      }

      $oMedia->original_filename = $filePath . $oMedia->original_filename;

      if (!empty($oMedia->tablet_name)) {
        $oMedia->tablet_name = $filePath . $oMedia->tablet_name;
      }

      if (!empty($oMedia->phone_name)) {
        $oMedia->phone_name = $filePath . $oMedia->phone_name;
      }

      if (!empty($oMedia->thumbnail)) {
        $oMedia->thumbnail = $filePath . $oMedia->thumbnail;
      }

      //dd($oMedia);
      return $oMedia;
    } else {
      return NULL;
    }
  }

}
