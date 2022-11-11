<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBkksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bkks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aspirator_id')->nullable();
            $table->foreignId('master_jenis_id')->nullable();
            $table->text('uraian')->nullable();
            $table->enum('tipe_kegiatan', ['pengadaan', 'infrasktruktur'])->nullable();
            $table->string('nilai_bkk')->nullable();
            $table->string('apbd')->nullable();
            $table->string('p_apbd')->nullable();
            $table->date('tanggal_realisasi')->nullable();
            $table->string('tahun')->nullable();
            $table->foreignId('kelurahan_id')->nullable();
            $table->text('alamat')->nullable();
            $table->string('lng')->nullable();
            $table->string('lat')->nullable();
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('bkks');
    }
}
