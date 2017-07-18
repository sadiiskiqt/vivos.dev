<?php

namespace Module\GoogleAnalytics\Models;

use Atlantis\Models\Base;

class GoogleAnalytics extends Base {

  protected $table = "googleanalytics";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'tracking_code',
      'tag_manager_code',
      'active'
  ];

}
