<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = ['name','cost_KFD','cost_EKF', 'minutes','user_id','type_id'];
    protected $appends = ['type_name'];

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
       return $this->belongsToMany(Component::class)->withPivot(['quantity','id'])->withTimestamps();
    }

    public function getTypeNameAttribute()
    {
        $type = Type::find($this->type_id);
        return $type->name;
    }  
        
}
