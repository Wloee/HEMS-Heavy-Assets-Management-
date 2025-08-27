<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addendum', function (Blueprint $table) {
            $table->id('id_addendum')->comment('Primary Key');
            $table->foreignId('id_proyek')->constrained('proyek', 'id_proyek')
                ->onUpdate('cascade')->onDelete('cascade')
                ->comment('ID Proyek');
            $table->string('nama_addendum', 50)->comment('Nama Satuan');
            $table->string('image_addendum', 100)->comment('Nomor Addendum');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addendum');
    }
};
