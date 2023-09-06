<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSubSection extends Model
{
    use HasFactory;


    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'sub_section_id');
    }
}
