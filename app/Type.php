<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //

    protected $fillable = ['name','kind','user_id'];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }   
}
