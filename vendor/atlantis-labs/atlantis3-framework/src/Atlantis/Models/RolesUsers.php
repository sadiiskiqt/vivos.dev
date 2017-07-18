<?php

namespace Atlantis\Models;

class RolesUsers extends Base {

  protected $table = "roles_users";

  public function user() {
    return $this->belongsTo('\User');
  }
  
  public function role() {
     return $this->belongsTo('\Atlantis\Models\Role');
  }

}