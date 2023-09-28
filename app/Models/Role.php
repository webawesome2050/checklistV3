<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function permission()
    {
        return $this->belongsToMany(Permission::class);
    }

    const ROLES = [
        'admin' => 'admin',
        'Checker' => 'Checker',
        'approver' => 'approver',
    ];

    

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}
