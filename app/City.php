<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $fillable = ['id', 'state_id', 'name' ];

    public function City()
    {
        return $this->belongsTo('App\State', 'state_id');
    }
}
