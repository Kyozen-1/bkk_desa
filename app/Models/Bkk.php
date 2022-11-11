<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bkk extends Model
{
    protected $table = 'bkks';
    protected $guarded = 'id';

    public function aspirator()
    {
        return $this->belongsTo('App\Models\Aspirator', 'aspirator_id');
    }

    public function master_jenis()
    {
        return $this->belongsTo('App\Models\MasterJenis', 'master_jenis_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo('App\Models\Kelurahan', 'kelurahan_id');
    }
}
