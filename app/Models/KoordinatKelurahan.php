<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KoordinatKelurahan extends Model
{
    protected $table = 'koordinat_kelurahans';
    protected $guarded = 'id';

    public function kelurahan()
    {
        return $this->belongsTo('App\Models\Kelurahan', 'kelurahan_id');
    }
}
