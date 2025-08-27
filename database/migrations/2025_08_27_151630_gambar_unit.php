<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('gambar_unit', function (Blueprint $table) {
            $table->id('id_gambar');
            $table->foreignId('unit_id')->constrained('unit', 'id_unit')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('gambar_depan')->nullable();
            $table->string('gambar_belakang')->nullable();
            $table->string('gambar_kiri')->nullable();
            $table->string('gambar_kanan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gambar_unit');
    }
};
