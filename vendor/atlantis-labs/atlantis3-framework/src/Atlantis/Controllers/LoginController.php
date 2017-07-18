<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag as MessageBag;
use Atlantis\Models\Repositories\MultiSitesSessionsRepository;

class LoginController extends Controller {

    public function index(\Request $request) {

        if (env('MULTI_SITES', FALSE)) {

            return $this->multiLogin($request);
        } else {

            return $this->regularLogin($request);
        }

    }

    public function logout() {

        if (env('MULTI_SITES', FALSE)) {

            $multiSitesConfig = config('multi-sites');

            if (!\Atlantis\Helpers\Tools::isMasterSite($multiSitesConfig)) {

                $masterSite = \Atlantis\Helpers\Tools::getMasterSite($multiSitesConfig);

                if ($masterSite != NULL) {
                    /*
                     * Redirect to master site to sign out
                     */
                    \Auth::logout();
                    $url = request()->root();
                    return \Redirect($masterSite['domain'] . "/admin/logout?redirect=" . urlencode($url));
                } else {
                    \Auth::logout();
                    $errors = new MessageBag(['password' => ['Master site is not set on config/multi-sites.php']]);
                    return \Redirect::back()->withErrors($errors)->withInput(\Input::except('password'));
                }
            } else {

                MultiSitesSessionsRepository::deleteSession($this->getLoginSession(), \Request::ip(), \Auth::user()->id);

                \Auth::logout();

                if (\Input::get('redirect') != NULL) {

                    return \Redirect(urldecode(\Input::get('redirect')));
                } else {
                    return \Redirect(\URL::to("/"));
                }
            }
        } else {

            \Auth::logout();
            return \Redirect(\URL::to("/"));
        }

    }

    /*
     * MULTI SITES
     * 
     * Set login session from master site
     */

    public function setLoginSession(\Request $request) {

        $multiSitesConfig = config('multi-sites');

        $masterSite = \Atlantis\Helpers\Tools::getMasterSite($multiSitesConfig);

        if ($masterSite != NULL) {

            $url = $masterSite['domain'] . '/get-logged-user';
            $myvars = 'ip=' . \Request::ip()
                    . '&logged_session=' . \Input::get('session')
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
            }
        }

        return \Redirect("admin");

    }

    /*
     * MULTI SITES
     * 
     * Check if master site have valid session in DB
     * and return logged user id.
     * 
     * This function wait cURL postRequest.
     */

    public function getLoggedUser(\Illuminate\Http\Request $request) {

        //$session = $request->get('logged_session');
        $ip = $request->get('ip');
        $multi_sites_key = $request->get('key');

        $multiSitesConfig = config('multi-sites');

        if ($multiSitesConfig['key'] == $multi_sites_key) {

            $multisitesSessions = MultiSitesSessionsRepository::getSessionByIP($ip);

            if (!$multisitesSessions->isEmpty()) {

                return $multisitesSessions->first()->logged_user;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }

    }

    /*
     * Return login view
     */

    private function getLoginView() {

        $_page = new \stdClass();

        $_page->seo_title = "Atlantis Login";

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

        return view('atlantis::auth/login', $aParams);

    }

    /*
     * Regular/normal/standard login
     */

    private function regularLogin(\Request $request) {

        if (\Auth::check() && \Auth::user()->hasRole('admin-login')) {

            if (\Input::get('redirect') != NULL) {

                if (\Input::get('with_auth_session') != NULL) {

                    $login_session = $this->getLoginSession();
                    return \Redirect(urldecode(\Input::get('redirect')) . '?session=' . $login_session);
                }

                return \Redirect(urldecode(\Input::get('redirect')));
            }

            //redirect to admin/dashboard
            return $this->redirectToAdmin();
        } else {

            if ($request::isMethod('post')) {

                $field = filter_var(\Input::get('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

                return $this->authAttempt([$field => \Input::get('username'), 'password' => \Input::get('password')]);
            } else {

                return $this->getLoginView();
            }
        }

    }

    /*
     * MULTI SITES
     * Login with multi sites
     */

    private function multiLogin(\Request $request) {

        if (\Auth::check() && \Auth::user()->hasRole('admin-login')) {

            if (\Input::get('redirect') != NULL) {

                if (\Input::get('with_auth_session') != NULL) {

                    $login_session = $this->getLoginSession();

                    MultiSitesSessionsRepository::setSession($login_session, 1, \Request::ip(), \Auth::user()->id);

                    return \Redirect(urldecode(\Input::get('redirect')) . '?session=' . $login_session);
                }

                return \Redirect(urldecode(\Input::get('redirect')));
            }

            //redirect to admin/dashboard
            return $this->redirectToAdmin();
        } else {

            $multiSitesConfig = config('multi-sites');

            if (!\Atlantis\Helpers\Tools::isMasterSite($multiSitesConfig)) {

                $masterSite = \Atlantis\Helpers\Tools::getMasterSite($multiSitesConfig);

                if ($masterSite != NULL) {
                    /*
                     * Redirect to master site with user credentials
                     */
                    $url = request()->root() . '/set-login-session';
                    return \Redirect($masterSite['domain'] . "/admin?redirect=" . urlencode($url) . '&with_auth_session=1');
                } else {
                    $errors = new MessageBag(['password' => ['Master site is not set on config/multi-sites.php']]);
                    return \Redirect::back()->withErrors($errors)->withInput(\Input::except('password'));
                }
            } else {

                return $this->regularLogin($request);
            }
        }

    }

    /*
     * Try to log in user
     */

    private function authAttempt($credentials) {

        if (\Auth::attempt($credentials, 1)) {

            $login_session = $this->getLoginSession();

            if (env('MULTI_SITES', FALSE) && \Atlantis\Helpers\Tools::isMasterSite(config('multi-sites'))) {
                MultiSitesSessionsRepository::setSession($login_session, 1, \Request::ip(), \Auth::user()->id);
            }

            if (\Input::get('redirect') != NULL) {

                if (\Input::get('with_auth_session') != NULL) {

                    return \Redirect(urldecode(\Input::get('redirect')) . '?session=' . $login_session);
                }

                return \Redirect(urldecode(\Input::get('redirect')));
            }

            return $this->redirectToAdmin();
        } else {

            $errors = new MessageBag(['password' => ['Username or Password Invalid.']]);
            return \Redirect::back()->withErrors($errors)->withInput(\Input::except('password'));
        }

    }

    /**
     * MULTI SITES
     * 
     * Get login session if this site is master
     */
    private function getLoginSession() {

        $sessions = \Illuminate\Support\Facades\Session::all();

        $login_session = NULL;

        foreach ($sessions as $key => $value) {
            if (stristr($key, "login_")) {
                $login_session = $key;
            }
        }

        return $login_session;

    }

    private function redirectToAdmin() {

        if (isset(auth()->user()->id)) {

            $userPermissions = \Atlantis\Models\Repositories\PermissionsRepository::getAllPermissionsForUser(auth()->user()->id);
            $aUserPerm = array();
            foreach ($userPermissions as $user_perm) {
                $aUserPerm[] = $user_perm->type;
            }

            $aUserPerm = array_unique($aUserPerm);

            $routes = \Route::getRoutes();

            foreach ($routes as $route) {

                $action = $route->getAction();

                if (isset($action['identifier']) && isset($action['name']) && $this->isAllowed($aUserPerm, $action['identifier'])) {
                    \Event::fire('admin.login');
                    return \Redirect($action['menu_item_url']);
                }
            }
        }

        return \Redirect("admin/dashboard/index");

    }

    private function isAllowed($aUserPerm, $identifier) {

        $allow = FALSE;

        if (auth()->user()->hasRole('admin') || in_array($identifier, $aUserPerm)) {
            $allow = TRUE;
        }

        return $allow;

    }

}
