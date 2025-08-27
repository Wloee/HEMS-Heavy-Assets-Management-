<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('pemilik_unit', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('nama_pemilik', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemilik_unit');
    }
};
