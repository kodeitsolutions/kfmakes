<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','kind','user_id','category_id'];

    protected $perPage = 7;

    //Relationships
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    } 
    //End Relationships
}
