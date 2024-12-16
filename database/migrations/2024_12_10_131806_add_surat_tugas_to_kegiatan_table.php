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
            Schema::table('kegiatan', function (Blueprint $table) {
                $table->string('surat_tugas')->nullable()->after('kegiatan_id')->comment('File surat tugas kegiatan');
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
                $table->dropColumn('surat_tugas');
            });
        }
    
};
