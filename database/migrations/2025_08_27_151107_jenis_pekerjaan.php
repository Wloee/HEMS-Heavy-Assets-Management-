<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('jenis_pekerjaan', function (Blueprint $table) {
            $table->id('id_jenis_pekerjaan');
            $table->string('nama_jenis_pekerjaan', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_pekerjaan');
    }
};
