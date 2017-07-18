<?php

namespace Atlantis\Middleware;

class Permissions {

  public function handle($request, \Closure $next, $identifier, $roleUsersModel, $permissionsModel) {

    $user = $request->user();    
    //check if user exist
    if ($user != NULL) {

      //admin have full access
      if ($user->hasRole('admin')) {
        return $next($request);
      }
     
      //user can edit own details
      if ($identifier == \Atlantis\Controllers\Admin\AdminController::$_ID_USERS && $request->getPathInfo() == '/admin/users/edit/' . $request->user()->id) {
        return $next($request);
      }
      
      $roleUser = new $roleUsersModel;

      //get roles for user
      $roles = $roleUser->getRoles($user->id);
      //$role_id = $roles->first()->role_id;

      $allow = FALSE;

      foreach ($roles as $role) {

        $findType = $permissionsModel::findType($identifier, $role->role_id);

        if ($findType->isEmpty()) {
          //continue searching in modules
          $findModule = $permissionsModel::findModule($role->role_id, $identifier);

          if (!$findModule->isEmpty()) {            
            $allow = TRUE;
          }
        } else {
          //get first result and check if value is (int)0
          if (!$findType->first()->value == 0) {
            $allow = TRUE;
          }
        }
      }
    }

    if ($allow) {
      return $next($request);
    } else {
      //redirect to 'Permissions denied page'
      return view('atlantis-admin::error', ['error' => 'Permissions denied']);
    }
  }  
  
}
