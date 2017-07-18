<?php

namespace Atlantis\Models; 

class Config extends Base {
  
  protected $table = "config";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'config_key',
      'config_value'
  ];
  
}