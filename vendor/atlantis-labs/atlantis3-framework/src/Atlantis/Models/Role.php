<?php

namespace Atlantis\Models;

class Role extends Base {

  protected $table = "roles";
  
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'description'
  ];

  public function users() {
    return $this->hasMany('\Atlantis\Models\RolesUsers', 'role_id');
  }
  
  public function permissions() {
    return $this->hasMany('\Atlantis\Models\Permissions', 'role_id');
  }

}