<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class merchant extends Model
{
    //
    protected $fillable = ['user_id'
        , 'name'
        , 'reg_num'
        , 'description'
        , 'address1'
        , 'address2'
        , 'postcode'
        , 'city'
        , 'state'
        , 'country'
        , 'phone_num'
        , 'fax_num'
        , 'mobile_num'
        , 'website'
        , 'ic_num'
        , 'is_fixed'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
