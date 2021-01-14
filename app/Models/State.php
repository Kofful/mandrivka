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

    public function transfers() {
        return $this->hasMany('App\Models\Transfer');
    }
}
