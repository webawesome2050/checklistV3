<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckListItem extends Model
{
    use HasFactory;

    protected $table = 'check_list_items';

    protected $fillable = [
        'id',
        'name',
        'section_id',
        'sub_section_id',
        'sub_sub_section_id',
        'check_list_id',
        'm_frequency',
        'c_frequency',
        'a_frequency'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'sub_section_id');
    }

    public function subSubSection()
    {
        return $this->belongsTo(SubSubSection::class, 'sub_sub_section_id');
    }

    public function checkList()
    {
        return $this->belongsTo(CheckList::class, 'check_list_id');
    }

     // Define a scope to get CheckListItem records by section
     public function scopeBySection($query, $sectionId)
     {
         return $query->where('section_id', $sectionId);
     }

}
