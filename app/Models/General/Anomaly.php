<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Anomaly extends Model
{
    protected $fillable = [
        'mqtt_log_id',
        'device_id',
        'sensor_type',
        'value',
        'min_value',
        'max_value'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function mqttLog()
    {
        return $this->belongsTo(MqttLog::class);
    }
}
