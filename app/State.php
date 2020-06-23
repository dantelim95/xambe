<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    protected $with = ['cities'];
    protected $fillable = ['id', 'country_id', 'name' ];

    public function State()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function cities()
    {
        return $this->hasMany('App\City', 'state_id', 'id')->select(['id', 'state_id', 'name']);
    }
}
