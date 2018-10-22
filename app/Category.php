<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','user_id'];

    protected $perPage = 7;

    //Relationships
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function types()
    {
        return $this->hasMany(Type::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    //End relationships
}
