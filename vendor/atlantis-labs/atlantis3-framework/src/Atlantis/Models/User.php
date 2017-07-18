<?php

namespace Atlantis\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Atlantis\Traits\Role as TraitRole;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword,
        TraitRole,
        Notifiable;

    public function roles() {
        return $this->hasMany('Atlantis\Models\RolesUsers', 'user_id');

    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'editor', 'language', 'widgets'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function sendPasswordResetNotification($token) {
        
        $this->notify(new \Atlantis\Notifications\PasswordResetNotification($token, $this));

    }
    
    /**
     * Get the user's widget attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getWidgetsAttribute($value) {

        if (empty($value)) {
            return array();
        } else {
            return unserialize($value);
        }

    }

    /**
     * Set the user's widget attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function setWidgetsAttribute($value) {

        if (empty($value)) {
            $this->attributes['widgets'] = serialize(array());
        } else {
            $this->attributes['widgets'] = serialize($value);
        }

    }

}
