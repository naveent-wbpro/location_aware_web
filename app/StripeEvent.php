<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeEvent extends Model
{
    public function user()
    {
        return $this->belongsTo('\App\User', 'stripe_id', 'customer_id');
    }
}
