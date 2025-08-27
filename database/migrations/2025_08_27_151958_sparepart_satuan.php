<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sparepart_satuan', function (Blueprint $table) {
            $table->id('id_sparepart_satuan');
            $table->foreignId('sparepart_id')->constrained('sparepart', 'id_sparepart')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('satuan_id')->constrained('satuan', 'id_satuan')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->integer('konversi')->default(1)->comment('Jumlah unit terkecil per satuan ini');
            $table->decimal('harga_satuan', 15, 2);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_satuan');
    }
};
