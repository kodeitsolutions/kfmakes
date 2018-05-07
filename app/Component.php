<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    //

    protected $fillable = ['name','cost','user_id','type_id'];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type()
    {
    	return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }
}
