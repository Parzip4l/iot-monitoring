<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('mqtt_logs', function (Blueprint $table) {
        $table->string('device_id')->nullable()->after('payload');
        $table->float('temperature')->nullable()->after('device_id');
        $table->float('humidity')->nullable()->after('temperature');
        $table->timestamp('recorded_at')->nullable()->after('humidity');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mqtt_logs');
    }
};
