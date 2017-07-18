<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class OptionFilter extends Base
{

    protected $table = "accommodations_option_filter";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'optionId',
        'roomId',
    ];

}