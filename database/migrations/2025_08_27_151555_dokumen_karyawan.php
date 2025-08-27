<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('dokumen_karyawan', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('image_ktp')->nullable()->comment('Path ke gambar KTP');
            $table->string('surat_lamaran')->nullable()->comment('Path ke file lamaran');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_karyawan');
    }
};
