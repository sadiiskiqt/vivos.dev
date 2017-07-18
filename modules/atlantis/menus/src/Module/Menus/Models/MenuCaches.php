<?php

namespace Module\Menus\Models;

use Atlantis\Models\Base;

class MenuCaches extends Base {

  protected $table = "menu_caches";
  
  protected  $guarded = [ 'id' ];
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'menu_id',
      'compiled'
  ];

}