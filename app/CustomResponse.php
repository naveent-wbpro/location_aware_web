<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomResponse extends Model
{
    public function field()
    {
        return $this->belongsTo('\App\FormField', 'form_field_id')->withTrashed();
    }
}
