<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormHat extends Model
{
    use HasFactory;

    protected $table = 'form_hat';

 
    protected $fillable = [
        'id',
        'name',
        'version',
        'issues_by',
        'notes',
        'created_at',
        'updated_at',
        'comments',
        'site_id',
        'date',
        'time',
        'room_temperature',
        'air_flow_rate',
        'temp_storage_3',
        'room_pressure',
        'verified_by'
     ];

     public function hat_lines()
     {
         return $this->hasMany(FormHatLine::class);
     }
}
