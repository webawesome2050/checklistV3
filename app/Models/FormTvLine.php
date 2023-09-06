<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTvLine extends Model
{
    use HasFactory;


protected $table =  "form_tv_lines"; 

   protected $fillable = [
        'form_tv_id',
        'date',
        'time',
        'temp_storage_1',
        'temp_storage_2',
        'temp_storage_3',
        'is_verified',
        'verified_by',
        'created_at',
        'updated_at'
     ];

     public function tv()
     {
        return $this->belongsTo(FormTv::class, 'form_tv_id');
     }


    
}
