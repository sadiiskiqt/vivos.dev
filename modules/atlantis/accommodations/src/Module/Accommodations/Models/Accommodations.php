<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class Accommodations extends Base
{

    protected $table = "accommodations";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'room_title',
        'body',
        'booking_link',
        'gallery_id',
    ];

}