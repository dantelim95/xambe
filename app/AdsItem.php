<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsItem extends Model
{
    //
    protected $table = 'adsitems';
    protected $fillable = ['user_id','address_id', 'category_id',
        'price', 'title', 'description', 'business_days', 'business_hours', 'delivery_methods',
        'delivery_time_frame'];

    public function AdsItem()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function pictures() {
        return $this->belongsToMany(Attachment::class, 'adsitem_attachment');
    }

}
