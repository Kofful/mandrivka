<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = ['state_id', 'nutrition_id', 'hotel', 'is_hot', 'description', 'min_age'];
    public $timestamps = false;

    public function state() {
        return $this->belongsTo('App\Models\State');
    }

    public function photos() {
        return $this->hasMany('App\Models\Photo');
    }

    public function nutrition() {
        return $this->belongsTo('App\Models\Nutrition');
    }
    public function rooms() {
        return $this->hasMany('App\Models\Room');
    }
}
