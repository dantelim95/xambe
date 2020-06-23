<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $with = ['states'];
    protected $fillable = ['id','name', 'code' ];

    public function Country()
    {
    }

    public function states()
    {
        return $this->hasMany('App\State', 'country_id', 'id')->select(['id', 'country_id', 'name']);
    }
}
