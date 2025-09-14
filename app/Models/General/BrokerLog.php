<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class BrokerLog extends Model
{
    protected $table = "broker_logs";

    protected $fillable = [
        'broker_ip',
        'broker_port',
        'status',
        'connected_at',
        'disconnected_at',
    ];
}
