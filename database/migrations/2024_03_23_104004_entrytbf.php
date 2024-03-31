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
        Schema::create('EntryTBF', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->char('TW', 2);
            $table->char('tahun', 4);
            $table->char('kec_id', 7);
            $table->boolean('status')->default('0'); //0= belum di entry , 1= sudah dientry, 2= Terkirim,3 revisi dinas,4 disetujui dinas,5.direvisi BPS, 6. disetujui BPS
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
        Schema::dropIfExists('EntryTBF');
    }
};
