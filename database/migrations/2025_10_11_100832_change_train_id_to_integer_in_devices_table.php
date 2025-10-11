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
        // Langkah 1: Hapus default value yang lama (yang bertipe string)
        DB::statement('ALTER TABLE devices ALTER COLUMN train_id DROP DEFAULT;');

        // Langkah 2: Ubah tipe data kolom menggunakan CASTING
        DB::statement('ALTER TABLE devices ALTER COLUMN train_id TYPE INTEGER USING (train_id::integer);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan prosesnya: ubah tipe kembali ke string
        DB::statement('ALTER TABLE devices ALTER COLUMN train_id TYPE VARCHAR(255);');

        // Kembalikan default value yang lama (asumsi string kosong)
        DB::statement("ALTER TABLE devices ALTER COLUMN train_id SET DEFAULT '';");
    }
};
