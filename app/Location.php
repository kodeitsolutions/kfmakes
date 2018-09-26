<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','telephone','in_charge','country','user_id'];

    //Relationships
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }    

    public function articles()
    {
       return $this->belongsToMany(Article::class)->withPivot(['stock','id'])->withTimestamps();
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }
    //End Relationships
}
