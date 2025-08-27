<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_schedule', function (Blueprint $table) {
            $table->id('id_schedule');
            $table->foreignId('unit_id')->constrained('unit', 'id_unit')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('jenis_maintenance', 100);
            $table->integer('interval_jam')->nullable()->comment('Setiap berapa jam operasi');
            $table->integer('interval_hari')->nullable()->comment('Setiap berapa hari');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_schedule');
    }
};
