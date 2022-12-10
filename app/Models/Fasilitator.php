<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitator extends Model
{
    protected $table = 'fasilitators';
    protected $guarded = 'id';

    public function akun_fasilitator()
    {
        return $this->hasOne('App\AkunFasilitator', 'fasilitator_id');
    }
}
