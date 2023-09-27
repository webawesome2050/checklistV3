<?php

namespace App\Models;

use App\Models\SubSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubSectionItem extends Model
{
    use HasFactory;

    protected $table = 'sub_section_items';


    protected $fillable = [
        'name',
        'section_id'
    ];

    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'section_id');
    }

}
