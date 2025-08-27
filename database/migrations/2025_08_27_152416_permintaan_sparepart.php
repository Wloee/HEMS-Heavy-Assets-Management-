<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permintaan_sparepart', function (Blueprint $table) {
            $table->id('id_permintaan');
            $table->foreignId('id_karyawan')->constrained('karyawan', 'id_karyawan')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->date('tanggal_permintaan');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_sparepart');
    }
};
