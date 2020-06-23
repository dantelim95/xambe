<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $fillable = ['user_id', 'address1', 'address2', 'postcode', 'city', 'state', 'country', 'contact_number'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
