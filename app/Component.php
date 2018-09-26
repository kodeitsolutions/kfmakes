<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','cost','user_id','type_id'];

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

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity','id')->withTimestamps();
    }
    //End relationships

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
