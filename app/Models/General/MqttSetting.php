<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class MqttSetting extends Model
{
    protected $fillable = ['topic', 'device_id', 'interval'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
