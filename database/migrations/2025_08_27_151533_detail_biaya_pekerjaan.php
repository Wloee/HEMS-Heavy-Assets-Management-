<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_biaya_pekerjaan', function (Blueprint $table) {
            $table->id('id_detail_biaya');
            $table->foreignId('proyek_id')->constrained('proyek', 'id_proyek')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('jenis_pekerjaan_id')->constrained('jenis_pekerjaan', 'id_jenis_pekerjaan')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya_total', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_biaya_pekerjaan');
    }
};
