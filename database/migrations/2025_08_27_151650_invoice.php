<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->foreignId('proyek_id')->constrained('proyek', 'id_proyek')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->date('tanggal_invoice');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('jumlah_tagihan', 15, 2);
            $table->decimal('sisa_piutang', 15, 2)->nullable();
            $table->enum('status', ['draft', 'terkirim', 'dibayar_sebagian', 'lunas', 'jatuh_tempo'])->default('draft');
            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice');
    }
};
