<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    //

    protected $fillable = ['motive','date','article_id','location_id','quantity','comment','user_id','moved'];
    protected $appends = ['name'];

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

    public function getFormatDate($value)
    {   
        return date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }

    public function setFormatDate($value)
    {
        $this->attributes['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $value)));
    }

    public function dateView()
    {
        return date("d/m/y", strtotime($this->date));
    }

    public function getNameAttribute()
    {
        $article = Article::find($this->article_id);
        return $article->name;
    }
}
