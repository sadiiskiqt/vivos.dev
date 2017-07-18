<?php

namespace Atlantis\Models;

class Permissions extends Base {

  protected $table = "permissions";

   /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'role_id',
      'type',
      'value'
  ];
  
  public function role() {
     return $this->belongsTo('\Atlantis\Models\Role');
  }
}