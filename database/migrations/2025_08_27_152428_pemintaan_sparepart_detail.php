<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permintaan_sparepart_detail', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_permintaan')->constrained('permintaan_sparepart', 'id_permintaan')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_sparepart')->constrained('sparepart', 'id_sparepart')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah');
            $table->foreignId('satuan_id')->constrained('satuan', 'id_satuan')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('permintaan_sparepart_detail');
    }
};
