<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id('id_unit');
            $table->string('kode_unit', 50)->nullable()->unique();
            $table->string('nama_unit');
            $table->foreignId('jenis_unit_id')->constrained('jenis_unit', 'id_jenis_unit')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->string('merk', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->year('tahun_pembuatan')->nullable();
            $table->string('no_rangka', 100)->nullable()->unique();
            $table->string('no_mesin', 100)->nullable()->unique();
            $table->string('no_polisi', 20)->nullable();
            $table->foreignId('pemilik_id')->nullable()->constrained('pemilik_unit', 'id_pemilik')
                ->onUpdate('cascade')->onDelete('set null');
            $table->text('alamat_unit')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->bigInteger('jam_operasi')->default(0)->comment('Total jam operasi');
            $table->enum('status_kepemilikan', ['milik_sendiri', 'sewa', 'kontrak'])->default('milik_sendiri');
            $table->enum('status_kondisi', ['baik', 'perlu_maintenance', 'rusak'])->default('baik');
            $table->enum('status_operasional', ['operasional', 'maintenance', 'standby', 'tidak_aktif'])->default('standby');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['status_operasional', 'status_kondisi']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit');
    }
};
