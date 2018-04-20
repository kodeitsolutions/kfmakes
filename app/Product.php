<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['name','cost_KFD','cost_EKF', 'minutes','user_id','type_id'];

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type()
    {
    	return $this->belongsTo(Type::class, 'type_id', 'id');
    }

    public function components()
    {
       return $this->belongsToMany(Component::class)->withPivot('quantity')->withTimestamps();
    }
}
