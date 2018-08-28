<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function roles(){
        return $this->belongsToMany('App\Models\Role','admin_role','admin_id','role_id');
    }

    public function isSuperAdmin(){
        return $this->roles->contains('name','sad');
    }

    public function isAdmin(){
        return $this->roles->contains('name','ad');
    }
    public function isCoAdmin(){
        return $this->roles->contains('name','coad');
    }
    public function isMonitor(){
        return $this->roles->contains('name','mntr');
    }

}
