<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkInvitation extends Model
{
    /**
     * Returns the company that was invited into a network
     */
    public function company()
    {
        return $this->belongsTo('\App\Company');
    }

    /**
     * Returns the network in which the invite belongs to
     */
    public function network()
    {
        return $this->belongsTo('\App\Network');
    }
}
