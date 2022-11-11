<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenis extends Model
{
    protected $table = 'master_jenis';
    protected $fillable = ['nama'];
    protected $guarded = 'id';

    public function bkk()
    {
        return $this->hasMany('App\Models\Bkk', 'master_jenis_id');
    }
}
