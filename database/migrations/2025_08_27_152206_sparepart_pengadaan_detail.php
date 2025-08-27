<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('sparepart_pengadaan_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('pembelian_id')->constrained('sparepart_pengadaan', 'id_pembelian')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('sparepart', 'id_sparepart')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('satuan_id')->constrained('satuan', 'id_satuan')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->bigInteger('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->string('kode_sparepart')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_pengadaan_detail');
    }
};
