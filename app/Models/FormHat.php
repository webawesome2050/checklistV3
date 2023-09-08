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
        'site_id'
     ];

     public function hat_lines()
     {
         return $this->hasMany(FormHatLine::class);
     }
}
