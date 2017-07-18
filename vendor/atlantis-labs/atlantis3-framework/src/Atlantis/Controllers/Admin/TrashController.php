<?php

namespace Atlantis\Controllers\Admin;

use Atlantis\Controllers\Admin\AdminController;
use Atlantis\Models\Repositories\PageRepository;
use Atlantis\Models\Repositories\PatternRepository;
use Illuminate\Http\Request;

class TrashController extends AdminController {

  public function __construct() {

    parent::__construct(self::$_ID_TRASH);
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

    return view('atlantis-admin::trash', $aData);
  }

  public function getRestorePage($id = NULL) {

    if ($id != NULL) {

      $model = new PageRepository();
      $model->changeStatus($id, 1);

      \Session::flash('success', 'Page was restored');
    }

    return redirect()->back();
  }

  public function getDeletePage($id = NULL) {

    if ($id != NULL) {

      PageRepository::deletePage($id);

      \Session::flash('success', 'Page was deleted');
    }

    return redirect()->back();
  }

  public function postBulkActionPage(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          PageRepository::deletePage($id);
        }
        \Session::flash('success', 'Pages was deleted');
      } else if ($request->get('action') == 'bulk_restore') {

        $model = new PageRepository();

        foreach ($aIDs as $id) {
          $model->changeStatus($id, 1);
        }
        \Session::flash('success', 'Pages was restored');
      }
    }

    return redirect()->back();
  }
  
  public function getRestorePattern($id = NULL) {

    if ($id != NULL) {
      
      $model = new PatternRepository();
      $model->changeStatus($id, 1);

      \Session::flash('success', 'Pattern was restored');
    }

    return redirect()->back();
  }
  
  public function getDeletePattern($id = NULL) {

    if ($id != NULL) {

      PatternRepository::deletePattern($id);

      \Session::flash('success', 'Page was deleted');
    }

    return redirect()->back();
  }
  
  public function postBulkActionPattern(Request $request) {

    if ($request->get('bulk_action_ids') != NULL) {

      $aIDs = explode(',', $request->get('bulk_action_ids'));

      if ($request->get('action') == 'bulk_delete') {

        foreach ($aIDs as $id) {
          PatternRepository::deletePattern($id);
        }
        \Session::flash('success', 'Patterns was deleted');
      } else if ($request->get('action') == 'bulk_restore') {

        $model = new PatternRepository();

        foreach ($aIDs as $id) {
          $model->changeStatus($id, 1);
        }
        \Session::flash('success', 'Patterns was restored');
      }
    }

    return redirect()->back();
  }

  public function getEmpty() {
    
    PageRepository::deleteAllFromTrash();
    PatternRepository::deleteAllFromTrash();
    
    \Session::flash('success', 'Items was deleted');
    
    return redirect()->back();
    
  }
  
}
