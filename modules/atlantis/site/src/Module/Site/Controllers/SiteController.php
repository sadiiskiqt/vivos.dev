<?php

 namespace Module\Site\Controllers;

/*
 * Controller: Site
 * @Atlantis CMS
 * v 1.0
 */

use App\Http\Controllers\Controller;

class SiteController extends Controller
{


  public function __construct()
  {


  }

  public function index()
  {

       return \View::make('site::blank' ,  [ 'msg'  => "Demo" ] );

  }

}
