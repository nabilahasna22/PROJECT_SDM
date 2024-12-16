<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            // Menambahkan kolom periode_id
            $table->unsignedBigInteger('periode_id')->nullable(); // nullable jika tidak wajib

            // Menambahkan foreign key constraint
            $table->foreign('periode_id')
                  ->references('periode_id') // kolom yang diacu di tabel periode_kegiatan
                  ->on('periode_kegiatan') // nama tabel yang menjadi referensi
                  ->onDelete('set null'); // Jika data periode_kegiatan dihapus, set periode_id ke null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            // Menghapus foreign key dan kolom periode_id
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};