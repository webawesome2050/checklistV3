<?php

namespace App\Models;

use App\Models\CheckList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemperatureChecklist extends Model
{
    use HasFactory;
    protected $table = 'temperature_check_list_items';

    protected $fillable = [
       'date',
       'time',
       'freezer1',
       'freezer2',
       'cool_room',
       'raw_material_chiller',
       'WIP_freezer',
       'blast_chiller_1',
       'blast_chiller_2',
       'cooked_WIP_chiller',
       'raw_WIP_chiller',
       'blast_freezer',
       'high_risk_area_chiller',
       'outer_cartooning_room',
       'factory_lunch_room_fridge',
       'office_staff_lunch_room_fridge',
       'checklist_id'
    ];

    public function checklist()
    {
        return $this->belongsTo(CheckList::class,'checklist_id');
    }
    


}
