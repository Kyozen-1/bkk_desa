<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aspirator extends Model
{
    protected $table = 'aspirators';
    protected $guarded = 'id';

    public function master_fraksi()
    {
        return $this->belongsTo('App\Models\MasterFraksi', 'master_fraksi_id');
    }
}
