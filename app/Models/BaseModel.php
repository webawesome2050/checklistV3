<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        // Add other timestamp columns if needed
    ];

    /**
     * Boot the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Set the default timezone for all timestamp columns
        static::creating(function ($model) {
            foreach ($model->getDates() as $dateColumn) {
                $model->{$dateColumn} = now()->timezone('Australia/Sydney');
            }
        });
    }
}
