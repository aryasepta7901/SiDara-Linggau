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
        Schema::create('tanaman', function (Blueprint $table) {
            $table->char('id', 10)->primary()->unique();
            $table->string('nama_tanaman');
            $table->char('jenis_sph', 3);
            $table->string('satuan_luas', 20);
            $table->string('satuan_produksi', 20);
            $table->string('bentuk_produksi', 50);
            $table->boolean('belum_habis', 1); // 0->tidak ada , 1->ada,
            $table->char('urut_kues', 3);
            $table->char('jenis_tanaman', 2);
            $table->double('min_harga', 10, 0);
            $table->double('max_harga', 10, 0);
            $table->string('satuan_harga', 20);
            $table->double('min_produktivitas', 6, 2);
            $table->double('max_produktivitas', 6, 2);
            $table->string('satuan_produktivitas', 20);

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
        Schema::dropIfExists('tanaman');
    }
};
