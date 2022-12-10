<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkunFasilitatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akun_fasilitators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('color_layout')->nullable();
            $table->string('nav_color')->nullable();
            $table->string('placement')->nullable();
            $table->string('behaviour')->nullable();
            $table->string('layout')->nullable();
            $table->string('radius')->nullable();
            $table->foreignId('fasilitator_id')->nullable()->references('id')->on('fasilitators')->onUpdate('cascade')->onDelete('restrict');
            $table->rememberToken();
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
        Schema::dropIfExists('akun_fasilitators');
    }
}
