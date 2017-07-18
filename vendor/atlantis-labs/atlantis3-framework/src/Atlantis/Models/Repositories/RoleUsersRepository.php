<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\RolesUsers;
use Atlantis\Models\Role;

/**
 *   get all roles 
 *    foreach( \Auth::user()->find(1)->getRoles() as $role ) {
  echo $role->role->name;
  }
 * 
 * //if user has role
 * \Auth::user()->hasRole( "admin ");
 * 
 * remove role 
 * 
 * \Auth::user()->removeRole("editor");
 * 
 * 
 */
class RoleUsersRepository implements \Atlantis\Models\Interfaces\IRoleUsersInterface {

  public function getRoles($userID) {

    return RolesUsers::with('Role')->where("user_id", "=", $userID)->get();
  }

  public function addRole($userID, $roleName) {


    $r = new RolesUsers();

    $r->role_id = $this->getRoleIDByName($roleName);

    $r->user_id = $userID;

    $r->save();
  }

  public function hasRole($userID, $roleName) {


    $model = RolesUsers::leftJoin('roles', function($join) {
              $join->on('roles_users.role_id', '=', 'roles.id');
            })
            ->where('roles.name', '=', $roleName)
            ->where('roles_users.user_id', '=', $userID)
            ->first([
        'roles.id',
        'roles.name'
    ]);

    return $model == NULL ? FALSE : TRUE;

    /*

      $result = RolesUsers::with(array('role' => function($query) use ($roleName) {
      $query->where("name", "=", $roleName);
      }))
      ->where("id", "=", $userID)
      ->count();
      dd($result);
      return $result == 1 ? true : false;
     * 
     */
  }

  public function removeRole($userID, $roleName) {

    $r = new RolesUsers();

    $r->where("role_id", "=", $this->getRoleIDByName($roleName))
            ->where("user_id", "=", $userID)
            ->delete();
  }

  public function getRoleIDByName($name) {

    $role = new Role();

    return $role->where("name", "=", $name)->first()->id;
  }

  public static function addRoleByID($user_id, $role_id) {
    
    $model = new RolesUsers();
    $model->user_id = $user_id;
    $model->role_id = $role_id;
    $model->save();
    
  }
  
  public static function getRolesByUserID($user_id) {
    return RolesUsers::where('user_id', '=', $user_id)->get();
  }
  
  public static function deleteRolesByUserID($user_id) {
    RolesUsers::where('user_id', '=', $user_id)->delete();
  }
  
}
