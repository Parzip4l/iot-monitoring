<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('train_config', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama kereta
            $table->text('description')->nullable();
            $table->integer('total_gerbong')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('train_config');
    }
};
