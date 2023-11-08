<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTv extends Model
{
    use HasFactory;

    protected $table = 'form_tv';

 
    protected $fillable = [
        'id',
        'name',
        'version',
        'issues_by',
        'notes',
        'created_at',
        'updated_at',
        'date',
        'time',
        'temp_storage_1',
        'temp_storage_2',
        'temp_storage_2',
        'is_verified',
        'verified_by'
     ];

     public function tv_lines()
     {
         return $this->hasMany(FormTvLine::class);
     }
}
