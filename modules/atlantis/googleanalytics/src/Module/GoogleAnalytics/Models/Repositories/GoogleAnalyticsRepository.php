<?php

namespace Module\GoogleAnalytics\Models\Repositories;

use Module\GoogleAnalytics\Models\GoogleAnalytics;

class GoogleAnalyticsRepository {

  public static function get($id) {
    return GoogleAnalytics::find($id);
  }

  public static function update($id, $data) {
    
    $model = GoogleAnalytics::find($id);
    //dd($data);
    if ($model != NULL) {
      $model->update($data);
      return TRUE;
    } else {
      return FALSE;
    }
  }
   
}