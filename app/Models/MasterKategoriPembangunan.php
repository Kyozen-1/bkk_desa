<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKategoriPembangunan extends Model
{
    protected $table = 'master_kategori_pembangunans';
    protected $guarded = 'id';

    public function bkk()
    {
        return $this->hasMany('App\Models\Bkk', 'master_kategori_pembangunan_id');
    }
}
