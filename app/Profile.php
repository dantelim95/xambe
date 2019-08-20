<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $fillable = ['user_id', 'first_name', 'last_name', 'birth_date', 'gender', 'picture'];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
