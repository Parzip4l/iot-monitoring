<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table="system_logs";
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
