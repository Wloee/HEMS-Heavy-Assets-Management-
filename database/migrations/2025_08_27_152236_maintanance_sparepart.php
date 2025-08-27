<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
       public function up()
    {
        Schema::create('maintenance_sparepart', function (Blueprint $table) {
            $table->id('id_maintenance_sparepart');
            $table->foreignId('maintenance_id')->constrained('maintenance_log', 'id_maintenance')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('sparepart', 'id_sparepart')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('jumlah_digunakan', 10, 2);
            $table->foreignId('satuan_id')->constrained('satuan', 'id_satuan')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_sparepart');
    }
};