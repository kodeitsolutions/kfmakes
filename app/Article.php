<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id','product_id','user_id','name','stock'];

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

	public function product()
    {
    	return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }   

    public function locations()
    {
       return $this->belongsToMany(Location::class)->withPivot(['stock','id'])->withTimestamps();
    }
    //End Relationships
}
