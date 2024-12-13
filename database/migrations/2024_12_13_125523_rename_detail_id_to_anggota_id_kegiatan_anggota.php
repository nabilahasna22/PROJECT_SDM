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
            // Rename the column
            $table->renameColumn('detail_id', 'anggota_id');

            // Drop the existing foreign key constraints
            $table->dropForeign('fk_detail_kegiatan_kegiatan_id');
            $table->dropForeign('fk_detail_kegiatan_nip');

            // Add the new foreign key constraints
            $table->foreign('anggota_id', 'fk_kegiatan_anggota_anggota')
                  ->references('anggota_id')
                  ->on('kegiatan_anggota') // Replace with the actual referenced table name
                  ->onDelete('cascade');

            $table->foreign('kegiatan_id', 'fk_kegiatan_anggota_kegiatan')
                  ->references('kegiatan_id')
                  ->on('kegiatan')
                  ->onDelete('cascade');

            $table->foreign('nip', 'fk_kegiatan_anggota_nip')
                  ->references('nip')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Update related tables to use the new column name
        Schema::table('persetujuan', function (Blueprint $table) {
            $table->dropForeign('fk_persetujuan_detail');
            $table->renameColumn('detail_id', 'anggota_id');
            $table->foreign('anggota_id', 'fk_persetujuan_anggota')
                  ->references('anggota_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('persetujuan', function (Blueprint $table) {
            $table->dropForeign('fk_persetujuan_anggota');
            $table->renameColumn('anggota_id', 'detail_id');
            $table->foreign('detail_id', 'fk_persetujuan_detail')
                  ->references('detail_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });

        Schema::table('kegiatan_anggota', function (Blueprint $table) {
            $table->dropForeign('fk_kegiatan_anggota_anggota');
            $table->dropForeign('fk_kegiatan_anggota_kegiatan');
            $table->dropForeign('fk_kegiatan_anggota_nip');
            $table->renameColumn('anggota_id', 'detail_id');
            $table->foreign('detail_id', 'fk_detail_kegiatan_kegiatan_id')
                  ->references('kegiatan_id')
                  ->on('kegiatan')
                  ->onDelete('cascade');
            $table->foreign('nip', 'fk_detail_kegiatan_nip')
                  ->references('nip')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
