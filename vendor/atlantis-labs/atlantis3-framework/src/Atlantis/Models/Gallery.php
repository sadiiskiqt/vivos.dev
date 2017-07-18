<?php

namespace Atlantis\Models;

class Gallery extends Base {

  protected $table = "galleries";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'description',
      'images'
  ];
  
  protected  $guarded = [ 'id' ];

  
}