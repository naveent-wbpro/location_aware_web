<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    public function codeSnippetWebsite()
    {
        return $this->belongsTo('\App\CodeSnippetWebsite');
    }

    public function fields()
    {
        return $this->hasMany('\App\FormField');
    }
}
