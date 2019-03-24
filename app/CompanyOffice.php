<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyOffice extends Model
{
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }
}
