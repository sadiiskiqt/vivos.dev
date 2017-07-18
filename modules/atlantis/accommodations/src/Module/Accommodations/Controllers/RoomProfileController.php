<?php

namespace Module\Accommodations\Controllers;

use App\Http\Controllers\Controller;
use Module\Accommodations\Models\Repositories\AccommodationsFilterRepository as AccommodationsFilterRepository;
use Illuminate\Http\Request;
use Module\Accommodations\Models\Accommodations;


class RoomProfileController extends Controller
{


    public function getIndex($id = null, $name = null)
    {


        return \View::make('accommodations::rooms/room-profile');
    }

}
