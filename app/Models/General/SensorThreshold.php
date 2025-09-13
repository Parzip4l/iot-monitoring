<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class SensorThreshold extends Model
{
    protected $table="sensor_thresholds";
    protected $fillable = [
        'device_id',
        'sensor_type',
        'min_value',
        'max_value'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
