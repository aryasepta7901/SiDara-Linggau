<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 18)->primary()->unique(); //nip
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->char('kec_id', 7)->nullable();
            $table->foreign('kec_id')->references('id')->on('kecamatan')->onDelete('cascade');


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
        Schema::dropIfExists('users');
    }
};
