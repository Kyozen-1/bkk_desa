<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AkunFasilitator extends Authenticatable
{
    use Notifiable;

    protected $table = 'akun_fasilitators';
    protected $guarded = 'id';

    public function fasilitator()
    {
        return $this->belongsTo('App\Models\Fasilitator', 'fasilitator_id');
    }
}
