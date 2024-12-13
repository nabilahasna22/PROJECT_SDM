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
        Schema::create('manajemen_pic', function (Blueprint $table) {
            $table->id('pic_id');
            $table->unsignedBigInteger('kegiatan_id');
            $table->string('nip', 20);
            $table->timestamps();

            $table->foreign('kegiatan_id')
                  ->references('kegiatan_id')
                  ->on('kegiatan_anggota')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('manajemen_pic');
    }
};