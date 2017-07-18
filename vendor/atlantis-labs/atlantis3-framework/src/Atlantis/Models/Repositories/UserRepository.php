<?php

namespace Atlantis\Models\Repositories;

use Atlantis\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserRepository {

  public function validationCreate($data, $user_id = NULL) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'name' => 'required|min:5|max:50|unique:users,name,' . $user_id,
        'email' => 'required|email|unique:users,email,' . $user_id,
        'password' => 'required|min:5|max:50',
        'password_confirm' => 'required_with:password|same:password'
    ];

    $messages = [
        'required' => trans('admin::validation.required'),
        'unique' => trans('admin::validation.unique'),
        'email' => trans('admin::validation.email'),
        'min' => trans('admin::validation.min'),
        'max' => trans('admin::validation.max'),
        'required_with' => trans('admin::validation.required_with'),
        'same' => trans('admin::validation.same')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    //$validator = $this->addReplacers($validator);

    return $validator;
  }

  public function validationUpdate($data, $user_id = NULL) {

    /**
     *  Validation rules for create
     * 
     * @var array
     */
    $rules_create = [
        'name' => 'required|min:5|max:50|unique:users,name,' . $user_id,
        'email' => 'required|email|unique:users,email,' . $user_id,
        'password' => 'min:5|max:50',
        'password_confirm' => 'required_with:password|same:password'
    ];

    $messages = [
        'required' => trans('admin::validation.required'),
        'unique' => trans('admin::validation.unique'),
        'email' => trans('admin::validation.email'),
        'min' => trans('admin::validation.min'),
        'max' => trans('admin::validation.max'),
        'required_with' => trans('admin::validation.required_with'),
        'same' => trans('admin::validation.same')
    ];

    $validator = Validator::make($data, $rules_create, $messages);

    //$validator = $this->addReplacers($validator);

    return $validator;
  }

  public function addUser($data) {

    $data['password'] = Hash::make($data['password']);

    $model = User::create($data);

    $eventData['roles'] = array();

    if (isset($data['roles'])) {
      foreach ($data['roles'] as $role_id) {
        RoleUsersRepository::addRoleByID($model->id, $role_id);
        $eventData['roles'][$role_id] = RolesRepository::getRole($role_id)->name;
      }
    }

    $eventData['user_id'] = $model->id;
    $eventData['name'] = $model->name;
    $eventData['email'] = $model->email;

    /** Fire the user.created event * */
    \Event::fire('user.created', [$eventData]);

    return $model->id;
  }

  public function updateUser($id, $data) {

    if (isset($data['password']) && empty($data['password'])) {
      unset($data['password']);
    } else {
      $data['password'] = Hash::make($data['password']);
    }

    $model = User::find($id);

    if ($model != NULL) {
      
      if (!isset($data['widgets'])) {
        $data['widgets'] = array();
      }
      
      $model->update($data);

      $eventData['roles'] = array();

      RoleUsersRepository::deleteRolesByUserID($model->id);
      if (isset($data['roles'])) {
        foreach ($data['roles'] as $role_id) {
          RoleUsersRepository::addRoleByID($model->id, $role_id);
          $eventData['roles'][$role_id] = RolesRepository::getRole($role_id)->name;
        }
      }
     
      $eventData['user_id'] = $model->id;
      $eventData['name'] = $model->name;
      $eventData['email'] = $model->email;

      /** Fire the user.updated event * */
      \Event::fire('user.updated', [$eventData]);
    }
  }

  public static function getUser($id) {
    return User::find($id);
  }

  public static function deleteUser($id) {

    $user = User::find($id);

    if ($user != NULL) {

      $eventData['user_id'] = $user->id;
      $eventData['name'] = $user->name;
      $eventData['email'] = $user->email;

      $user->delete();
      RoleUsersRepository::deleteRolesByUserID($id);

      /** Fire the user.updated event * */
      \Event::fire('user.deleted', [$eventData]);
    }
  }

}
