<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTvSite2 extends Model
{
    use HasFactory;


    protected $table = 'form_tv_site2';

 
    protected $fillable = [
        'id',
        'name',
        'version',
        'issues_by',
        'notes',
        'created_at',
        'updated_at',
        'date',
        'time',
        'base_area_f1',
        'base_area_f2',
        'base_area_cool_room',
        'medium_area_cool_freezer',
        'medium_area_cool_chiller1',
        'medium_area_cool_chiller2',
        'medium_area_cool_cooked_wip_chiller',
        'medium_area_cool_wip_chiller',
        'high_area_cool_freezer',
        'high_area_cool_chiller',
        'outer_cartooning_room',
        'factory_lunch_room_fridge',
        'office_staff_lunch_room_fridge',
        'is_verified',
        'verified_by'
     ];

     public function tv_lines()
     {
         return $this->hasMany(FormTvLineSite2::class);
     }
}
