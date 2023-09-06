<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSection extends Model
{
    use HasFactory;
    protected $table = 'sub_sections';

    protected $fillable = [
        'name',
        'section_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
