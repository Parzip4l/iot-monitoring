<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;

class Device extends Model
{
    protected $table="devices";
    protected $fillable = [
        'name',
        'serial_number',
        'status',
        'topic',
        'user_id',
        'car_id',
        'train_id',
        'broker_ip',
        'broker_port',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function train()
    {
        return $this->belongsTo(TrainConfig::class,'train_id');
    }

    public function cars()
    {
        return $this->belongsTo(TrainCars::class,'car_id');
    }
}
