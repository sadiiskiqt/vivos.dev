<?php

namespace Atlantis\Models\Interfaces;

interface IRoleUsersInterface {
  
  public function getRoles( $userID );
  
  public function addRole( $userID , $roleID ); 
  
  public function hasRole( $userID, $roleName );
  
  public function removeRole( $userId, $roleName);
  
}