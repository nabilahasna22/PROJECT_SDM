<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
  public function up()
    {
        // Step 1: Create a temporary table to hold the data
        Schema::create('kegiatan_anggota_temp', function (Blueprint $table) {
            $table->id('anggota_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->string('nip', 20);
            $table->decimal('bobot', 10, 2);
            $table->enum('status_ajuan', ['Diajukan', 'Disetujui', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
        });

        // Copy data from the original table to the temporary table
        DB::table('kegiatan_anggota')->cursor()->each(function ($row) {
            DB::table('kegiatan_anggota_temp')->insert([
                'anggota_id' => $row->detail_id,
                'kegiatan_id' => $row->kegiatan_id,
                'nip' => $row->nip,
                'bobot' => $row->bobot,
                'status_ajuan' => $row->status_ajuan,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        });

        // Step 2: Drop the original table and rename the temporary table
        Schema::dropIfExists('kegiatan_anggota');
        Schema::rename('kegiatan_anggota_temp', 'kegiatan_anggota');

        // Add the new foreign key constraints
        Schema::table('kegiatan_anggota', function (Blueprint $table) {
            $table->foreign('anggota_id', 'fk_kegiatan_anggota_anggota')
                  ->references('anggota_id')
                  ->on('your_referenced_table') // Replace with the actual referenced table name
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

        Schema::dropIfExists('kegiatan_anggota');
        Schema::rename('kegiatan_anggota_temp', 'kegiatan_anggota');

        Schema::table('kegiatan_anggota', function (Blueprint $table) {
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
