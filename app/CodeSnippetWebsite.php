<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CodeSnippetWebsite extends Model
{
    use SoftDeletes;

    /* Company in which code snippet belongs to
     */
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    public function network()
    {
        return $this->belongsTo('\App\Network');
    }

    public function forms()
    {
        return $this->hasMany('\App\Form');
    }
}
