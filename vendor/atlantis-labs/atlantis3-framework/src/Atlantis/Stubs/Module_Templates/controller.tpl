<?php

 namespace %mod_namespace%\%capital_name%\Controllers;

/*
 * Controller: %capital_name%
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;

class %capital_name%Controller extends Controller
{

use \%mod_namespace%\%capital_name%\Traits\%capital_name%Trait;

  public function index()
  {

       return \View::make('%lower_name%::blank' ,  [ 'msg'  => "Demo" ] );

  }

}
