<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('log_operasional', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('unit_proyek_id')->constrained('unit_proyek', 'id_unit_proyek')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_operasi');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->decimal('jam_operasi', 4, 2)->nullable()->comment('Jam operasi hari ini');
            $table->decimal('jam_idle', 4, 2)->nullable()->comment('Jam menganggur');
            $table->foreignId('operator_id')->nullable()->constrained('karyawan', 'id_karyawan')
                ->onUpdate('cascade')->onDelete('set null');
            $table->string('lokasi_kerja')->nullable();
            $table->string('jenis_pekerjaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['tanggal_operasi']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_operasional');
    }
};
