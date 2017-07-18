<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use \Illuminate\Http\Request;
use Atlantis\Models\Repositories\PatternRepository;
use Atlantis\Helpers\Iterator;
use Atlantis\Helpers\Tools;

class PatternsController extends AdminController {

  private $aTypes = [
      'text' => 'Text',
      'view' => 'View',
      'hmvc' => 'Resource'
  ];
  public static $_STATUSES = [
      1 => 'Active',
      0 => 'Deactivated'
  ];

  public function __construct() {

    parent::__construct(self::$_ID_PATTERNS);
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

    return view('atlantis-admin::patterns', $aData);
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

    $aData['aTypes'] = $this->aTypes;

    $aData['aViews'] = $this->getViews();

    $aData['aLang'] = Tools::getThemeLanguages();

    $aData['aStatuses'] = self::$_STATUSES;

    $aData['variables'] = $this->getThemeVariables();

    $aData['oLatestPatterns'] = PatternRepository::latestEditedPatterns(5);

    return view('atlantis-admin::patterns-add', $aData);
  }

  public function postAdd(Request $request) {

    $oPatterns = new PatternRepository();

    $data = $request->all();

    $validator = $oPatterns->validationCreate($data);

    if (!$validator->fails()) {

      $pattern_id = $oPatterns->createPattern($data);

      \Session::flash('success', 'Pattern ' . $data['name'] . ' was created');

      if ($request->get('_update')) {
        return redirect('admin/patterns/edit/' . $pattern_id);
      } else {
        return redirect('admin/patterns');
      }
    } else {
      //dd($validator->errors()->all());
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
      $oPattern = PatternRepository::getPatternWithActiveVersion($id, $lang);

      if ($oPattern == NULL) {
        $oPattern = PatternRepository::getPatternWithActiveVersion($id);

        if ($oPattern != NULL) {
          $aData['msgInfo'][] = 'This pattern does not have a version in your default language.';
        }
      }
    } else {
      //get page by version
      $oPattern = PatternRepository::getPatternByVersion($id, $version_id, $lang);
    }

    if ($oPattern != NULL) {

      $aData['oPattern'] = $oPattern;

      $aData['data_status'] = $this->getDataStatus($oPattern);

      $aData['oFields'] = \Atlantis\Models\Repositories\PatternsFieldsRepository::getByPattern($id);

      $oTags = \Atlantis\Models\Repositories\TagRepository::getTagsByResourceID(AdminController::$_ID_PATTERNS, $id);

      $aTags = array();

      foreach ($oTags as $tag) {
        $aTags[] = $tag->tag;
      }

      $aData['tags'] = implode(',', $aTags);

      $oMasks = \Atlantis\Models\Repositories\PatternsMasksRepository::getByPattern($id);

      $aMask = array();

      foreach ($oMasks as $mask) {
        $aMask[] = $mask->mask;
      }

      $aData['masks'] = implode("\n", $aMask);

      $aData['start_date'] = Tools::getExpirationDateForView($oPattern->start_date);
      $aData['end_date'] = Tools::getExpirationDateForView($oPattern->end_date);

      $aData['aTypes'] = $this->aTypes;

      $aData['variables'] = $this->getThemeVariables();

      $aData['aViews'] = $this->getViews();

      $aData['aLang'] = Tools::getThemeLanguages();

      $aData['aStatuses'] = self::$_STATUSES;

      $aData['oLatestPatterns'] = PatternRepository::latestEditedPatterns(5);
    } else {
      $aData['invalid_item'] = 'This pattern is not valid';
    }

    return view('atlantis-admin::patterns-edit', $aData);
  }

  public function postEdit($id = NULL, Request $request) {
    //dd($id, $request->all());
    if ($id != NULL) {

      $oPatterns = new PatternRepository();

      $data = $request->all();

      $validator = $oPatterns->validationCreate($data, $id);

      if (!$validator->fails()) {

        $oPatterns->updatePattern($id, $data);

        \Session::flash('success', 'Pattern ' . $data['name'] . ' was updated');

        if ($request->get('_update')) {
          return redirect('admin/patterns/edit/' . $id);
        } else {
          return redirect('admin/patterns');
        }
      } else {
        //dd($validator->errors()->all());
        return redirect()->back()->withErrors($validator)->withInput();
      }
    }
  }

  public function postClonePattern(Request $request, $id = NULL, $lang = NULL) {
    //dd($id, $lang);
    if ($id != NULL) {

      if ($lang == NULL) {
        $lang = config('atlantis.default_language');
      }

      $data = $request->all();

      $oPattern = new PatternRepository();

      $validator = $oPattern->validationClone($data);

      if (!$validator->fails()) {

        $cloned = $oPattern->clonePattern($id, $data, auth()->user()->id, auth()->user()->name, $lang);

        if ($cloned !== FALSE) {
          \Session::flash('success', 'Pattern was cloned.');
          return redirect('admin/patterns/edit/' . $cloned->id);
        } else {
          \Session::flash('error', 'Ooops something went wrong.');
        }
      } else {

        $msgs = implode('<br>', $validator->errors()->all());

        \Session::flash('error', $msgs);
      }
    } else {
      \Session::flash('error', 'Invalid pattern.');
    }
    return redirect()->back();
  }

  public function getDeletePattern($pattern_id) {

    $prevUrl = parse_url(\Illuminate\Support\Facades\URL::previous());

    $oPage = new PatternRepository();
    $oPage->changeStatus($pattern_id, 5);

    \Session::flash('success', 'Pattern was deleted');

    if (isset($prevUrl['path']) && $prevUrl['path'] == '/admin/dashboard') {

      $query = '';

      if (isset($prevUrl['query'])) {
        $query = '?' . $prevUrl['query'];
      }

      return redirect('admin/dashboard' . $query);
    } else {
      return redirect('admin/patterns');
    }
  }

  public function postBulkActionVersions(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {

          $version = \Atlantis\Models\Repositories\PatternsVersionsRepository::getVersion($id);

          if ($version != NULL) {
            if ($version->active != 1) {
              $version->delete();
            }
          }
        }

        AtlantisCache::clearAll();

        \Session::flash('success', 'Versions was deleted');
      }
    }

    return redirect()->back();
  }

  public function postBulkAction(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      $oPattern = new PatternRepository();

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          $oPattern->changeStatus($id, 5);
        }
        \Session::flash('success', 'Pattern was deleted');
      } else if ($request->get('action') == 'bulk_deactivate') {

        foreach ($aIDs as $id) {
          $oPattern->changeStatus($id, 0);
        }
        \Session::flash('success', 'Pattern was deactivated');
      } else if ($request->get('action') == 'bulk_activate') {

        foreach ($aIDs as $id) {
          $oPattern->changeStatus($id, 1);
        }
        \Session::flash('success', 'Pattern was activated');
      }
    }

    return redirect()->back();
  }

  public function getMakeActiveVersion($id = NULL, $version_id = NULL, $lang = NULL) {

    \Atlantis\Models\Repositories\PatternsVersionsRepository::makeActiveVersion($id, $version_id, $lang);

    return redirect()->back();
  }

  public function getDeleteVersion($id = NULL, $version_id = NULL, $lang = NULL) {

    \Atlantis\Models\Repositories\PatternsVersionsRepository::deleteVersion($id, $version_id, $lang);

    \Session::flash('success', 'Version ' . $version_id . ' was deleted');

    return redirect()->back();
  }

  private function getViews() {

    $aT[NULL] = '';

    if (\Atlantis\Helpers\Themes\ThemeTools::haveActiveTheme()) {

      $aTemp = Iterator::getFiles('/' . config('atlantis.theme_path') . "/views/pattern", "WITHOUT EXT", TRUE, FALSE);

      foreach ($aTemp as $temp) {

        $aElem = explode("/", $temp);

        if (!in_array("disabled", $aElem)) {

          $stripTemp = str_replace('.blade', '', $temp);

          $aT[$stripTemp] = $stripTemp;
        }
      }
    }
    return $aT;
  }

  private function getDataStatus($oPattern) {

    if ($oPattern->status == 0) {
      $status = 'disabled';
    } else if ($oPattern->status == 1) {
      $status = 'active';
    } else if ($oPattern->status == 5) {
      $status = 'disabled';
    } else {
      $status = 'disabled';
    }

    return $status;
  }

  private function getThemeVariables() {

    $pattVariables = \Atlantis\Helpers\Themes\ThemeTools::getPatternVariables();

    $vars = array();

    if (isset($pattVariables['pattern_outputs'])) {

      foreach ($pattVariables['pattern_outputs'] as $var => $desc) {
        $vars[$var] = $var;
      }
    }

    return $vars;
  }

}
