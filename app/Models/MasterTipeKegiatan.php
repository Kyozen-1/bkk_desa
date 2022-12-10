<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterTipeKegiatan extends Model
{
    protected $table = 'master_tipe_kegiatans';
    protected $guarded = 'id';

    public function bkk()
    {
        return $this->hasMany('App\Models\Bkk', 'master_tipe_kegiatan_id');
    }
}
