<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_karyawan', 100);
            $table->char('no_nik', 16)->nullable()->unique()->comment('Nomor NIK KTP');
            $table->date('tanggal_lahir');
            $table->string('no_handphone', 15)->nullable()->comment('Nomor WhatsApp');
            $table->date('tanggal_bergabung');
            $table->foreignId('departemen_id')->constrained('departemen', 'id_departemen')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('posisi_id')->constrained('posisi', 'id_posisi')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('Gaji', 10, 2)->nullable();
            $table->decimal('Tunjangan', 10, 2)->nullable();
            $table->decimal('Intensif', 10, 2)->nullable();
            $table->enum('Status', ['Aktif', 'Cuti', 'TidakAktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
};
