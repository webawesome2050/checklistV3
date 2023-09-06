<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckListDetail extends Model
{
    use HasFactory;

    protected $table = 'check_lists_detail';
    
    protected $fillable = [
        'title',
        'issued_at',
        'issued_version',
        'checklist_id',
        'date_of_inspection',
        'start_time',
        'finish_time',
        'inspected_by',
        'approved_by',
    ];

    public function checklist()
    {
        return $this->belongsTo(CheckList::class,'checklist_id');
    }
    
}
