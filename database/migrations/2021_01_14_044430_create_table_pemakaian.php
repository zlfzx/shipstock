<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePemakaian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemakaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id');
            $table->unsignedBigInteger('kapal_id');
            $table->integer('jumlah');
            $table->timestamp('tanggal_pemakaian');
            $table->timestamps();

            $table->foreign('sparepart_id')->references('id')->on('sparepart')->onDelete('cascade');
            $table->foreign('kapal_id')->references('id')->on('kapal')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemakaian');
    }
}
