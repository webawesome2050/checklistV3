<?php

namespace App\Models;

use App\Models\SubSectionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function subSectionItems()
    {
        return $this->hasMany(SubSectionItem::class);
    }


}
