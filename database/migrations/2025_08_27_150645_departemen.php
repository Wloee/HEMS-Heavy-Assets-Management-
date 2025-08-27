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
        Schema::create('departemen', function (Blueprint $table) {
            $table->id('id_departemen');
            $table->string('nama_departemen', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departemen');
    }
};

