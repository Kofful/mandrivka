<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    public function transfersFrom() {
        return $this->hasMany('App\Models\Transfer', 'state1_id');
    }

    public function transfersTo() {
        return $this->hasMany('App\Models\Transfer', 'state2_id');
    }
}
