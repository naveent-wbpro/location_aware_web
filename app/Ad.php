<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $date = [
        'posted_at',
        'created_at',
        'updated_at'
    ];
}
