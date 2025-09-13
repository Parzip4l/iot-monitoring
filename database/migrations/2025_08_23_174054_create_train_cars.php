<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('train_cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained('train_config')->cascadeOnDelete();
            $table->integer('car_number'); // Urutan gerbong
            $table->string('car_type')->nullable(); // Ekonomi, Eksekutif, Barang, dll
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('train_cars');
    }
};
