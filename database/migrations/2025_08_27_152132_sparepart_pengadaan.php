<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('sparepart_pengadaan', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->foreignId('supplier_id')->constrained('supplier', 'id_supplier')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->date('tanggal_pembelian');
            $table->decimal('total_harga', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_pengadaan');
    }
};
