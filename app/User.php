<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','superuser'
    ];

    protected $perPage = 7;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];    

    //Relationships
    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }
    //End relationships   

    /**
     * Check attribute superuser.
     *
     * @return attribute
     */
    public function isSuperuser()
    {
        return $this->superuser;
    }
}
