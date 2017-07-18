<?php

namespace Atlantis\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;

class PasswordController extends Controller {
  /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
   */

use ResetsPasswords;

  protected $redirectTo = '/admin/dashboard';

  /**
   * Create a new password controller instance.
   *
   * @param  \Illuminate\Contracts\Auth\Guard  $auth
   * @param  \Illuminate\Contracts\Auth\PasswordBroker  $passwords
   * @return void
   */
  public function __construct(Guard $auth, PasswordBroker $passwords) {
    $this->auth = $auth;
    $this->passwords = $passwords;
       
    $this->middleware('guest');
  }

  /**
   * Display the form to request a password reset link.
   *
   * @return \Illuminate\Http\Response
   */
  public function getEmail() {

    $_page = new \stdClass();

    $_page->seo_title = "Atlantis Password Reset";

    $_page->tracking_header = "";

    \Event::fire("page.body_class", [ 'password-reset', null, null, null]);

    $aParams = array();
    $aParams['_page'] = $_page;

    return view('auth.password', $aParams);
  }

  /**
   * Send a reset link to the given user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function postEmail(Request $request) {
      //dd($request->all());
    $messages = [
        'required' => trans('admin::validation.required'),
        //'email' => trans('admin::validation.email')
    ];

    $field = filter_var(\Input::get('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    
    $this->validate($request, [
        //'email' => 'required|email',
        'email' => 'required'
        ], $messages);

    $response = Password::sendResetLink([$field => $request->get('email')], function (Message $message) {
              $message->from('no-reply@atlantis-cms.com', 'AtlantisCMS WebMaster')
                      ->subject($this->getEmailSubject());
            });
    
    switch ($response) {
      case Password::RESET_LINK_SENT:
        return redirect()->back()->with('status', trans('admin::' . $response));

      case Password::INVALID_USER:
        return redirect()->back()->withErrors(['email' => trans('admin::' . $response)]);
    }
  }

  


  /**
   * Display the password reset view for the given token.
   *
   * @param  string  $token
   * @return \Illuminate\Http\Response
   */
  public function getReset($token = null) {

    if (is_null($token)) {
      throw new NotFoundHttpException;
    }

    $_page = new \stdClass();

    $_page->seo_title = "Atlantis Password Reset";

    $_page->tracking_header = "";

    \Event::fire("page.body_class", [ 'password-reset', null, null, null]);

    $aParams = array();
    $aParams['_page'] = $_page;

    return view('auth.reset', $aParams)->with('token', $token);
  }

  /**
   * Reset the given user's password.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function postReset(Request $request) {

    $messages = [
        'required' => trans('admin::validation.required'),
        'email' => trans('admin::validation.email'),
        'confirmed' => trans('admin::validation.confirmed'),
        'min' => trans('admin::validation.min')
    ];

    $this->validate($request, [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
            ], $messages);

    $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
    );

    $response = Password::reset($credentials, function ($user, $password) {
              $this->resetPassword($user, $password);
            });

    switch ($response) {
      case Password::PASSWORD_RESET:
        return redirect($this->redirectPath())->with('status', trans('admin::' . $response));

      default:
        return redirect()->back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => trans('admin::' . $response)]);
    }
  }

}
