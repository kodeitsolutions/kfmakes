<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','superuser'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $SoftDelete = true;

    public function isSuperuser()
    {
        return $this->superuser;
    }

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
        
        
}
