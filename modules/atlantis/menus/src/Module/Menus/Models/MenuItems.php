<?php

namespace Module\Menus\Models;

use Atlantis\Models\Base;

class MenuItems extends Base {

  protected $table = "menu_items";
  
  protected  $guarded = [ 'id' ];
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'menu_id',
      'parent_id',
      'child_id',
      'weight',
      'label',
      'url',
      'onclick',
      'class',
      'attributes'
  ];

}