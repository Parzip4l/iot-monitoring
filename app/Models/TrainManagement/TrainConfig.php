<?php

namespace App\Models\TrainManagement;

use Illuminate\Database\Eloquent\Model;

class TrainConfig extends Model
{
    protected $table="train_config";
    protected $fillable = [
        'name',
        'description',
        'total_gerbong',
    ];
}
