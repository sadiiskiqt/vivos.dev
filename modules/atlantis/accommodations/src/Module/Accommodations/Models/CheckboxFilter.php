<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class CheckboxFilter extends Base
{

    protected $table = "accommodations_checkbox_filter";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'checkboxId',
        'roomId',
    ];

}