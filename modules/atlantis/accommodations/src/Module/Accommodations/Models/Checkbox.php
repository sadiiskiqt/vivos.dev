<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class Checkbox extends Base
{

    protected $table = "accommodations_checkbox_category";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'sCheckboxTitle',
    ];

}