<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id('id_supplier');
            $table->string('nama_supplier', 100);
            $table->text('alamat')->nullable();
            $table->string('no_handphone', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier');
    }
};
