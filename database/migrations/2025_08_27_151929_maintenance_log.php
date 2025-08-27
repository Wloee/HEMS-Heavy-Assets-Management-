<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('maintenance_log', function (Blueprint $table) {
            $table->id('id_maintenance');
            $table->foreignId('unit_id')->constrained('unit', 'id_unit')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('schedule_id')->nullable()->constrained('maintenance_schedule', 'id_schedule')
                ->onUpdate('cascade')->onDelete('set null');
            $table->date('tanggal_maintenance');
            $table->enum('jenis_maintenance', ['rutin', 'perbaikan', 'darurat', 'overhaul'])->default('rutin');
            $table->text('deskripsi_pekerjaan')->nullable();
            $table->foreignId('teknisi_id')->nullable()->constrained('karyawan', 'id_karyawan')
                ->onUpdate('cascade')->onDelete('set null');
            $table->string('workshop')->nullable();
            $table->bigInteger('jam_operasi_saat_maintenance')->nullable();
            $table->decimal('biaya_jasa', 15, 2)->default(0.00);
            $table->decimal('biaya_sparepart', 15, 2)->default(0.00);
            $table->decimal('biaya_total', 15, 2)->nullable();
            $table->enum('status', ['dijadwalkan', 'dalam_proses', 'selesai', 'ditunda'])->default('dijadwalkan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['tanggal_maintenance']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_log');
    }
};
