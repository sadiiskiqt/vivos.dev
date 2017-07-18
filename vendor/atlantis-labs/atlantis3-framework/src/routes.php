<?php

/** Login page protected route * */
Route::any(config('page-protected.route_login'), 'Atlantis\Controllers\SiteLoginController@index');
/** Logout page protected route * */
Route::any(config('page-protected.route_logout'), 'Atlantis\Controllers\SiteLoginController@logout');



// Password reset link request routes...
Route::get('admin/password/email', 'Atlantis\Controllers\PasswordController@getEmail');
Route::post('admin/password/email', 'Atlantis\Controllers\PasswordController@postEmail');

// Password reset routes...
Route::get('admin/password/reset/{token}', 'Atlantis\Controllers\PasswordController@getReset');
Route::post('admin/password/reset', 'Atlantis\Controllers\PasswordController@postReset');




/** Login page route * */
Route::any('admin', 'Atlantis\Controllers\LoginController@index');

Route::any('set-login-session', 'Atlantis\Controllers\LoginController@setLoginSession');
Route::any('get-logged-user', 'Atlantis\Controllers\LoginController@getLoggedUser');

/** Logout page route * */
Route::get('admin/logout', 'Atlantis\Controllers\LoginController@logout');

/** Admin Dashboard Controller * */
/**
 * Note this call is a little bit different, basically this is the way to call 
 * dynamic methods, however they have to be prefixed with "get<MethodName>"
 */
Route::group(['prefix' => 'admin',
    'name' => '',
    'icon' => 'icon icon-Speedometter',
    'tooltip' => 'Dashboard',
    'menu_item_url' => 'admin/dashboard',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_DASHBOARD], function () {
  Route::controller('/dashboard', 'Atlantis\Controllers\Admin\DashboardController');
});

Route::group(['prefix' => 'admin',
    'name' => 'Pages',
    'menu_item_url' => 'admin/pages',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_PAGES], function () {
  Route::controller('/pages', 'Atlantis\Controllers\Admin\PagesController');
  Route::controller('/categories', 'Atlantis\Controllers\Admin\CategoriesController');
});

Route::group(['prefix' => 'admin',
    'name' => 'Patterns',
    'menu_item_url' => 'admin/patterns',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_PATTERNS], function () {
  Route::controller('/patterns', 'Atlantis\Controllers\Admin\PatternsController');
});

Route::group(['prefix' => 'admin',
    'name' => 'Modules',
    'menu_item_url' => 'admin/modules',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_MODULES], function () {
  Route::controller('/modules', 'Atlantis\Controllers\Admin\ModulesController');
});

Route::group(['prefix' => 'admin',
    'name' => 'Media',
    'menu_item_url' => 'admin/media',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_MEDIA], function () {
  Route::controller('/media', 'Atlantis\Controllers\Admin\MediaController');
});

Route::group(['prefix' => 'admin',
    'parent' => '',
    'parent-icon' => 'icon icon-Settings',
    'name' => 'Users',
    'menu_item_url' => 'admin/users',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_USERS], function () {
  Route::controller('/users', 'Atlantis\Controllers\Admin\UsersController');
});

Route::group(['prefix' => 'admin',
    'parent' => '',
    'parent-icon' => 'icon icon-Settings',
    'name' => 'Roles',
    'menu_item_url' => 'admin/roles',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_ROLES], function () {
  Route::controller('/roles', 'Atlantis\Controllers\Admin\RolesController');
});

Route::group(['prefix' => 'admin',
    'parent' => '',
    'parent-icon' => 'icon icon-Settings',
    'name' => 'Themes',
    'menu_item_url' => 'admin/themes',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_THEMES], function () {
  Route::controller('/themes', 'Atlantis\Controllers\Admin\ThemesController');
});

Route::group(['prefix' => 'admin',
    'parent' => '',
    'parent-icon' => 'icon icon-Settings',
    'name' => 'Config',
    'menu_item_url' => 'admin/config',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_CONFIG], function () {
  Route::controller('/config', 'Atlantis\Controllers\Admin\ConfigController');
});

Route::group(['prefix' => 'admin',
    'parent' => '',
    'parent-icon' => 'icon icon-Settings',
    'name' => 'Trash',
    'icon' => '',
    'menu_item_url' => 'admin/trash',
    'identifier' => Atlantis\Controllers\Admin\AdminController::$_ID_TRASH], function () {
  Route::controller('/trash', 'Atlantis\Controllers\Admin\TrashController');
});


 //Route::controller('admin/trash', 'Atlantis\Controllers\Admin\TrashController');

/** Route to generate Google compatible xml sitemap * */
Route::get('sitemap.xml', 'Atlantis\Controllers\SiteMapController@index');

/** Reserve route for datatables */
Route::post('datatable-proccessing/getdata', 'Atlantis\Controllers\DataTableResolver@resolve');

/** Page with alternate lang specified * */
Route::any('{lang?}/{page?}', 'Atlantis\Controllers\PageController@index')
        ->where(["lang" => "[a-z]{2}", "page" => ".+"]);

/** Page with no lang specified * */
Route::any('{page?}', 'Atlantis\Controllers\PageController@index')
        ->where(["lang" => "[a-z]{2}", "page" => ".+"]);
