<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    public function state() {
        return $this->belongsTo('App\Models\State');
    }

    public function photos() {
        return $this->hasMany('App\Models\Photo');
    }
}
