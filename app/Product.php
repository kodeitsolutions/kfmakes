<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','cost_KFD','cost_EKF', 'minutes','user_id','type_id'];

    protected $perPage = 7;

    /**
     * The attributes added to model.
     *
     * @var array
     */
    protected $appends = ['type_name'];

    //Relationships
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
    //End Relationships

    /**
     * Get added attribute.
     *
     * @return attribute
     */
    public function getTypeNameAttribute()
    {
        $type = Type::find($this->type_id);
        return $type->name;
    }  
        
}
