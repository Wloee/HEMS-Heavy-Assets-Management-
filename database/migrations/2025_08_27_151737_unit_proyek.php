<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit_proyek', function (Blueprint $table) {
            $table->id('id_unit_proyek');
            $table->foreignId('unit_id')->constrained('unit', 'id_unit')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('proyek_id')->constrained('proyek', 'id_proyek')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->foreignId('operator_id')->nullable()->constrained('karyawan', 'id_karyawan')
                ->onUpdate('cascade')->onDelete('set null');
            $table->decimal('tarif_sewa_harian', 15, 2)->nullable();
            $table->enum('status', ['aktif', 'selesai', 'ditunda'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_proyek');
    }
};
