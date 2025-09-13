<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

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
}
