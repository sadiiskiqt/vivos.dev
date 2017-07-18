<?php

namespace Atlantis\Middleware;

class SiteAuth {

  public function handle($request, \Closure $next) {
    
    if (\Auth::check() === false) {

      return \Redirect(config('page-protected.route_login'));
    }

    if (\Auth::user()->hasRole('site-login')) {

      return $next($request);
    } else {
      return \Redirect(config('page-protected.route_login'));
    }
  }
  

}
