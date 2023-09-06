<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;

    protected $table = 'check_lists';

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'date_of_inspection',
        'finish_time',
        'start_time',
        'inspected_by',
        'site_id',
        'url',
        'label',
        'type_id',
        'is_approved',
        'comments'
    ];


    public function section()
    {
        return $this->hasMany(Section::class);
    }

    public function checkListItems()
    {
        return $this->hasMany(CheckListItem::class);
    }

    public function TemperatureChecklist()
    {
        return $this->hasMany(TemperatureChecklist::class,"checklist_id");
    }


    public function details()
    {
        return $this->hasOne(CheckListDetail::class,"checklist_id");
    }

    public function site()
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

}
