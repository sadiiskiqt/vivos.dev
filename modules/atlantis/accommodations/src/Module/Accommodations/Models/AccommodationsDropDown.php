<?php

namespace Module\Accommodations\Models;

use Atlantis\Models\Base;

class AccommodationsDropDown extends Base
{

    protected $table = "accommodations_dropdown_category";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'sDropDownTitle',
    ];

}