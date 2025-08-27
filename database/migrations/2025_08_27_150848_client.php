<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->id('id_client');
            $table->string('nama_client', 100);
            $table->text('alamat')->nullable();
            $table->string('no_handphone', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client');
    }
};
