<?php

namespace App\Models;

use App\Models\CheckListItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckListItemsEntry extends Model
{
    use HasFactory;

    protected $table = 'check_list_items_entries';

    protected $fillable = [
        'name',
        'section_id',
        'sub_section_id',
        'sub_sub_section_id',
        'check_list_id',
        'check_list_items_id',
        'visual_insp_allergen_free',
        'micro_SPC_swab',
        'chemical_residue_check',
        'TP_check_RLU',
        'comments_corrective_actions',
        'action_taken',
        'is_approved',
        'entry_id',
        'sub_section_items',
        'ATP_check_RLU'
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
        // return $this->belongsTo(CheckList::class, 'check_list_id');
        return $this->belongsTo(CheckList::class, 'entry_id');
    }

    public function checkListItem()
    {
        return $this->belongsTo(CheckListItem::class, 'check_list_items_id');
    }

}
