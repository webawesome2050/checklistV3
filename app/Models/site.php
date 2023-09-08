<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class site extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'code'];

    public function checkLists()
    {
        return $this->hasMany(CheckList::class);
        
    }

    public function hats()
    {
        return $this->hasMany(FormHat::class);
    }
    

    
}
