<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //
    public function getStripeAmountAttribute()
    {
        $amount = $this->amount * 100;

        return (integer) $amount;
    }
}
