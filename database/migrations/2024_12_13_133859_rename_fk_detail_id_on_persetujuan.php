<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * */
    public function up()
    {
     Schema::table('persetujuan', function (Blueprint $table) {
            // Drop the existing foreign key index
            $table->dropForeign('persetujuan_detail_id_foreign');
            
            // Re-add the foreign key with the new column name
            $table->foreign('anggota_id','fk_anggota_id_persetujuan')
                  ->references('anggota_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persetujuan', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign('persetujuan_detail_id_foreign');
            
            // Re-add the original foreign key
            $table->foreign('detail_id')
                  ->references('anggota_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });
    }
};