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
        Schema::table('kegiatan_anggota', function (Blueprint $table) {
            $table->enum('status_ajuan', ['Diajukan', 'Disetujui', 'Ditolak'])
                  ->default('Diajukan')
                  ->after('bobot');
        });
    }

    public function down()
    {
        Schema::table('kegiatan_anggota', function (Blueprint $table) {
            $table->dropColumn('status_ajuan');
        });
    }
};
