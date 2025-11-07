<?php

namespace App\Exports;

use App\Models\MqttLog;
use Maatwebsite\Excel\Concerns\FromCollection;

class MqttLogExport implements FromCollection
{
    public function collection()
    {
        return MqttLog::select('device_id', 'temperature', 'humidity', 'noise', 'timestamp')->get();
    }
}
