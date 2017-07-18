<?php

namespace Atlantis\Traits;

use Atlantis\Models\Repositories\RoleUsersRepository;

trait Role {
  
  public function addRole( $roleName ) {
    
     $r = new RoleUsersRepository();
     
     $r->addRole( $this->id , $roleName );
    
  }
  
  public function hasRole( $roleName ) {
    
    $r = new RoleUsersRepository();
    
    return $r->hasRole( $this->id , $roleName );
    
  }
  
  public function getRoles() {
    
    $r = new RoleUsersRepository();
    
    return $r->getRoles( $this->id);
    
  }
  
  public function removeRole( $roleName ) {
    
     $r = new RoleUsersRepository();
     
     return $r->removeRole($this->id, $roleName);
  }
  
  
}