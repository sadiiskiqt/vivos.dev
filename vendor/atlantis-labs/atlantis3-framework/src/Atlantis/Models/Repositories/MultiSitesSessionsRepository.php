<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\MultiSitesSessions;

class MultiSitesSessionsRepository {

  public static function setSession($key, $value, $ip, $logged_user_id) {

    $model = new MultiSitesSessions();
    $session = $model->where('key', '=', $key)
            ->where('ip', '=', $ip)
            ->where('logged_user', '=', $logged_user_id)
            ->get();
   
    if (!$session->isEmpty()) {
      $exist = MultiSitesSessions::find($session->first()->id);
      $exist->logged_user = $logged_user_id;
      $exist->key = $key;
      $exist->value = $value;
      $exist->ip = $ip;
      $exist->save();
    } else {
      $new = new MultiSitesSessions();
      $new->logged_user = $logged_user_id;
      $new->key = $key;
      $new->value = $value;
      $new->ip = $ip;
      $new->save();
    }
  }

  public static function deleteSession($key, $ip, $logged_user_id) {

    $model = new MultiSitesSessions();
    $session = $model->where('key', '=', $key)
            ->where('ip', '=', $ip)
            ->where('logged_user', '=', $logged_user_id)
            ->get();
    
    if (!$session->isEmpty()) {
      $exist = MultiSitesSessions::find($session->first()->id);
      $exist->delete();
    }
  }
  
  public static function getSessionByIP($ip) {
    
    $model = new MultiSitesSessions();
    return $model->where('ip', '=', $ip)
            ->get();
    
  }

}
