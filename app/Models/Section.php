<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'site_id'
    ];

    public function checklist()
    {
        return $this->belongsTo(CheckList::class, 'check_list_id');
    }

    public function site()
    {
        return $this->belongsTo(Section::class, 'site_id');
    }


}
