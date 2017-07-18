<?php

namespace Atlantis\Middleware;

class AdminAuth {

  public function handle($request, \Closure $next) {

    if (env('MULTI_SITES', FALSE)) {
      
      $multiSitesConfig = config('multi-sites');

      if (!\Atlantis\Helpers\Tools::isMasterSite($multiSitesConfig)) {

        if ($this->isLoggedMasterSite($multiSitesConfig)) {

          if (\Auth::user()->hasRole('admin-login')) {
            return $next($request);
          } else {
            return \Redirect("/");
          }
        } else {
          return \Redirect("/");
        }
      } else {

        return $this->authCheck($request, $next);
      }
    } else {

      return $this->authCheck($request, $next);
    }
  }

  private function authCheck($request, \Closure $next) {

    if (\Auth::check() === false) {

      return \Redirect("/");
    }

    if (\Auth::user()->hasRole('admin-login')) {

      return $next($request);
    } else {
      return \Redirect("/");
    }
  }

  /*
   * MULTI SITES
   * 
   * Check if master site is logged.
   * Set the same session from master site.
   */

  private function isLoggedMasterSite($multiSitesConfig) {

    $masterSite = \Atlantis\Helpers\Tools::getMasterSite($multiSitesConfig);

    if ($masterSite != NULL) {

     $url = $masterSite['domain'] . '/get-logged-user';
      $myvars = 'ip=' . \Request::ip()
              . '&key=' . $multiSitesConfig['key'];

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $myvars);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      $response = curl_exec($ch);

      \Auth::logout();

      if (!empty($response)) {

       \Auth::loginUsingId($response, 1);

        return TRUE;
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }

}
