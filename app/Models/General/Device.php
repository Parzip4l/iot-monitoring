<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table="devices";
    protected $fillable = [
        'name',
        'serial_number',
        'status',
        'topic',
        'user_id',
        'car_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
