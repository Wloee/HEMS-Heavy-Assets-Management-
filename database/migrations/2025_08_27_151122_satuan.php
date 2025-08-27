<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id('id_satuan')->comment('Primary Key');
            $table->string('nama_satuan', 50)->comment('Nama Satuan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuan');
    }
};
