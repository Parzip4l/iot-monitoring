<?php

namespace App\Models\TrainManagement;

use Illuminate\Database\Eloquent\Model;

class TrainCars extends Model
{
    protected $table="train_cars";
    protected $fillable = [
        'train_id',
        'car_number',
        'car_type',
    ];

    public function TrainConfig()
    {
        return $this->belongsTo(TrainConfig::class,'train_id');
    }
}
