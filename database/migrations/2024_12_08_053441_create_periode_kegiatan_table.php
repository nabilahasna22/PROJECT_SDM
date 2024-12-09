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
        Schema::create('periode_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('periode_id'); // id sebagai primary key dengan auto increment
            $table->year('tahun'); // kolom untuk tahun
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_kegiatan');
    }
};
