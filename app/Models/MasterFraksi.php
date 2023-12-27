<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterFraksi extends Model
{
    protected $table = 'master_fraksis';
    protected $guarded = 'id';

    public function aspirator()
    {
        return $this->hasMany('App\Models\Aspirator', 'master_fraksi_id');
    }
}
