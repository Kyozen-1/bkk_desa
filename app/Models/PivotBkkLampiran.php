<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PivotBkkLampiran extends Model
{
    public function bkk()
    {
        return $this->belongsTo('App\Models\Bkk', 'bkk_id');
    }
}
