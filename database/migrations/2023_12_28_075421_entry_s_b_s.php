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
        Schema::create('EntrySBS', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->char('bulan', 2);
            $table->char('tahun', 4);
            // $table->double('r4', 6, 2)->nullable();
            // $table->double('r5', 6, 2)->nullable();
            // $table->double('r6', 6, 2)->nullable();
            // $table->double('r7', 6, 2)->nullable();
            // $table->double('r8', 6, 2)->nullable();
            // $table->double('r9', 6, 2)->nullable();
            // $table->double('r10', 6, 2)->nullable();
            // $table->double('r11', 6, 2)->nullable();
            // $table->double('r12', 20, 0)->nullable();
            // $table->string('note')->nullable();
            // $table->char('tanaman_id', 10);
            $table->char('kec_id', 7);
            // $table->char('user_id', 18);
            // $table->boolean('status')->default('0'); // 0=tanaman_ada, 1=tanaman_habis,2= tanaman_baru
            $table->boolean('status')->default('0'); //0= belum di entry , 1= sudah dientry, 2= Terkirim,3 revisi dinas,4 disetujui dinas,5.direvisi BPS, 6. disetujui BPS
            // $table->boolean('revisi')->default('0'); //0= default , 1= Revisi, 2= Perbaikan revisi.
            // $table->string('catatan_dinas')->nullable();
            // $table->string('catatan_BPS')->nullable();
            // $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('EntrySBS');
    }
};
