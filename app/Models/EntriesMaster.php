<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntriesMaster extends Model
{
    use HasFactory;


    protected $table = 'check_list_items_entries_master';

    protected $fillable = [
        'id',
        'name'
    ];

    // public function section()
    // {
    //     return $this->belongsTo(Section::class, 'section_id');
    // }

    // public function subSection()
    // {
    //     return $this->belongsTo(SubSection::class, 'sub_section_id');
    // }

    // public function subSubSection()
    // {
    //     return $this->belongsTo(SubSubSection::class, 'sub_sub_section_id');
    // }

    public function checkLists()
    {
        return $this->hasMany(CheckListItemsEntry::class, 'entry_id');
    }

    //  // Define a scope to get CheckListItem records by section
    //  public function scopeBySection($query, $sectionId)
    //  {
    //      return $query->where('section_id', $sectionId);
    //  }
}
