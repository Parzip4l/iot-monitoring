<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use App\Models\General\Device;

class MqttLog extends Model
{
    protected $fillable = [
        'topic',
        'payload',
        'device_id',
        'temperature',
        'humidity',
        'timestamp',
        'last_saved_at',
        'noise'
    ];

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'serial_number');
    }
}
