<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['motive','date','article_id','location_id','quantity','comment','user_id','moved'];

    /**
     * The attributes added to model.
     *
     * @var array
     */
    protected $appends = ['name'];

    //Relationships
    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function article()
    {
    	return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function location()
    {
    	return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    //End relationships

    /**
     * Returns formatted date.
     *
     * @return date
     */
    public function getFormatDate($value)
    {   
        return date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }

    /**
     * Formats attribute date to store in database.
     *
     * @return date
     */
    public function setFormatDate($value)
    {
        $this->attributes['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }

    /**
     * Formats date to show in view.
     *
     * @return date
     */
    public function dateView()
    {
        return date("d/m/y", strtotime($this->date));
    }

    /**
     * Get added attribute.
     *
     * @return attribute
     */
    public function getNameAttribute()
    {
        $article = Article::find($this->article_id);
        return $article->name;
    }
}
