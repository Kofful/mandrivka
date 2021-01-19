<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'daterange', 'customer_name', 'customer_phone', 'room_id'];

    public function room() {
        return $this->belongsTo('App\Models\Room');
    }
}
