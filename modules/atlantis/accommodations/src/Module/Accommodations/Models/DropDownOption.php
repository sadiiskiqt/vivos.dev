<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class DropDownOption extends Base
{

    protected $table = "accommodations_dropdown_options";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'sOptionTitle',
        'iDropDownId',
        'iRoomId',
    ];

}