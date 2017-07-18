<?php
/*
 * Routes: Accommodations
 * @Atlantis CMS
 * v 1.0
 */

Route::controller('admin/modules/accommodations', 'Module\Accommodations\Controllers\Admin\AccommodationsAdminController');

Route::get('accommodations', 'Module\Accommodations\Controllers\AccommodationsController@build');

Route::get('getRooms', 'Module\Accommodations\Controllers\AccommodationsController@getRooms');

Route::get('getSearchVal', 'Module\Accommodations\Controllers\AccommodationsController@getSearchVal');

Route::get('autocomplete', 'Module\Accommodations\Controllers\AccommodationsController@autocomplete');