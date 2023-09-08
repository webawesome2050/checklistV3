<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormHatLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_tv_id',
        'date',
        'time',
        'room_temperature',
        'air_flow_rate',
        'room_pressure',
        'is_verified',
        'verified_by',
        'created_at',
        'updated_at'
     ];

     public function tv()
     {
        return $this->belongsTo(FormHat::class, 'form_tv_id');
     }
}
