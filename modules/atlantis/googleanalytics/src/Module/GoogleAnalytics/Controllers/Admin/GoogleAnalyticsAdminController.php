<?php

namespace Module\GoogleAnalytics\Controllers\Admin;

use Illuminate\Http\Request;
use Atlantis\Controllers\Admin\AdminModulesController;
use Module\GoogleAnalytics\Models\Repositories\GoogleAnalyticsRepository;

class GoogleAnalyticsAdminController extends AdminModulesController {

  public function __construct() {
    parent::__construct(\Config::get('googleanalytics.setup'));
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

    $model = GoogleAnalyticsRepository::get(1);
    
    if ($model->active == 'GTM') {
      $gtm = TRUE;
      $ga = FALSE;
    } else {
      $gtm = FALSE;
      $ga = TRUE;
    }
    
    $aData['is_gtm'] = $gtm;
    $aData['is_ga'] = $ga;
    
    $aData['model'] = $model;
    
    return view("googleanalytics-admin::admin/ga", $aData);
  }
  
  public function postUpdate(Request $request) {
    
    if (GoogleAnalyticsRepository::update(1, $request->all())) {
      \Session::flash('success', 'Updated');
    } else {
      \Session::flash('error', 'Erorr');
    }
    
    return redirect('admin/modules/googleanalytics');
    
  }

}
