<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotBkkLampiransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_bkk_lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bkk_id')->nullable();
            $table->foreign('bkk_id')->references('id')->on('bkks')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->enum('status', ['before', 'after'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_bkk_lampirans');
    }
}
