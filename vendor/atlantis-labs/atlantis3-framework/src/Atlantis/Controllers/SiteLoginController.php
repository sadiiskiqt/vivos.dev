<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag as MessageBag;

class SiteLoginController extends Controller {

  public function index(\Request $request) {

    if (\Auth::check() && \Auth::user()->hasRole('site-login')) {

      /**
        if (\Input::get('redirect') != NULL) {

        return \Redirect(urldecode(\Input::get('redirect')));
        }
       * 
       */
      //redirect to site-protected page

      return \Redirect(config('page-protected.route_after_login'));
    } else {

      if ($request::isMethod('post')) {

        return $this->authAttempt(['name' => \Input::get('username'), 'password' => \Input::get('password')]);
      } else {

        return $this->getLoginView($request);
      }
    }
  }

  public function logout() {

    \Auth::logout();
    return \Redirect(config('page-protected.route_login'));
  }

  /*
   * Return login view
   */

  private function getLoginView(\Request $request) {

    $pageController = new PageController(new \Atlantis\Models\Repositories\PageRepository(), new \Atlantis\Models\Repositories\PatternRepository());

    return $pageController->index($request);

    /**
      $_page = new \stdClass();

      $_page->seo_title = "";

      $_page->tracking_header = "";

      \Event::fire("page.body_class", [ 'login-page', null, null, null]);

      $aParams = array();
      $aParams['_page'] = $_page;

      $urlQuery = '';

      if (\Input::get('redirect') != NULL) {

      $urlQuery .= '?redirect=' . urlencode(\Input::get('redirect'));

      if (\Input::get('with_auth_session') != NULL) {
      $urlQuery .= '&with_auth_session=' . \Input::get('with_auth_session');
      }
      }

      $aParams['urlQuery'] = $urlQuery;

      return view('atlantis::page/site-login', $aParams);
     * 
     */
  }

  /*
   * Try to log in user
   */

  private function authAttempt($credentials) {

    if (\Auth::attempt($credentials, 1)) {

      /**
        if (\Input::get('redirect') != NULL) {

        return \Redirect(urldecode(\Input::get('redirect')));
        }
       * 
       */
      return \Redirect(config('page-protected.route_after_login'));
    } else {

      $errors = new MessageBag(['password' => ['Username or Password is Invalid.']]);
      if (config('page-protected.on_error_redirect_to') == '{{back}}' || empty(config('page-protected.on_error_redirect_to'))) {
        return \Redirect::back()->withErrors($errors)->withInput(\Input::except('password'));
      } else {
        return redirect(config('page-protected.on_error_redirect_to'))->withErrors($errors)->withInput(\Input::except('password'));
      }
    }
  }

}
