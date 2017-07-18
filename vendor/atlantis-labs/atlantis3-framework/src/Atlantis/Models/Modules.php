<?php

namespace Atlantis\Models;

class Modules extends Base {

  protected $table = "modules";
  protected $guarded = [ 'id'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'author',
      'version',
      'namespace',
      'path',
      'provider',
      'extra',
      'adminURL',
      'icon',
      'active',
      'description'
  ];

}
