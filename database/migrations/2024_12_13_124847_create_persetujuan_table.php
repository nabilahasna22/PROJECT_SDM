<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            $table->id('persetujuan_id');
            $table->unsignedBigInteger('detail_id');
            $table->string('pimpinan_nip', 20);
            $table->enum('status_persetujuan', ['Disetujui', 'Ditolak']);
            $table->timestamp('tanggal_persetujuan')->useCurrent();
            $table->timestamps();

            $table->foreign('detail_id')
                  ->references('detail_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('persetujuan');
    }
};