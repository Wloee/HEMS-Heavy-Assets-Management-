<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('sparepart', function (Blueprint $table) {
            $table->id('id_sparepart');
            $table->string('kode_sparepart', 50)->nullable()->unique();
            $table->string('nama_sparepart');
            $table->string('merk', 100)->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('supplier', 'id_supplier')
                ->onUpdate('cascade')->onDelete('set null');
            $table->bigInteger('stok_minimum')->default(5);
            $table->bigInteger('stok_saat_ini')->default(0);
            $table->text('deksripsi_produk')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['stok_saat_ini']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart');
    }
};
