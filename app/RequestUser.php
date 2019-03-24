<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestUser extends Model
{
    protected $table = 'request_user';

    protected $dates = [
        'acknowledged_at'
    ];

    public function request()
    {
        return $this->belongsTo('\App\Request');
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }
}
