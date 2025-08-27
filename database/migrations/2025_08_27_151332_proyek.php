<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proyek', function (Blueprint $table) {
            $table->id('id_proyek');
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai_aktual')->nullable();
            $table->bigInteger('id_addendum')->nullable();
            $table->string('nama_client');
            $table->text('lokasi_proyek')->nullable();
            $table->enum('status', ['draft', 'aktif', 'selesai', 'ditunda', 'dibatalkan'])->default('draft');
            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('proyek');
    }
};
