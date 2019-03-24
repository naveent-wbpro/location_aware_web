<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FormField extends Model
{
    use SoftDeletes;

    public function form()
    {
        return $this->belongsTo('\App\Form');
    }
}
