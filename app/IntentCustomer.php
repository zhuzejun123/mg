<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntentCustomer extends Model
{
    protected $table = 'intent_customers';

    public static $INTENT = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10
    ];

    public function setIntentPriceAttribute($value)
    {
        $this->attributes['intent_price'] = bcmul($value, 100);
    }

    public function getIntentPriceAttribute($value)
    {
        return bcdiv($value, 100, 2);
    }

    public function setFinalPriceAttribute($value)
    {
        $this->attributes['final_price'] = bcmul($value, 100);
    }

    public function getFinalPriceAttribute($value)
    {
        return bcdiv($value, 100, 2);
    }

    public function houses()
    {
        return $this->hasMany('App\House', 'id', 'id');
    }
}
