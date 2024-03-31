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
        Schema::create('BST', function (Blueprint $table) {
            $table->id();
            $table->double('r3', 8, 2)->nullable();
            $table->double('r4', 8, 2)->nullable();
            $table->double('r5', 8, 2)->nullable();
            $table->double('r6', 8, 2)->nullable();
            $table->double('r7', 8, 2)->nullable();
            $table->double('r8', 8, 2)->nullable();
            $table->double('r9', 8, 2)->nullable();
            $table->double('r10', 8, 2)->nullable();
            $table->double('r11', 20, 0)->nullable();
            $table->string('note')->nullable();
            $table->char('user_id', 18);
            $table->char('tanaman_id', 10);
            $table->char('entry_id', 36);
            $table->string('catatan_dinas')->nullable();
            $table->string('catatan_BPS')->nullable();
            $table->boolean('status')->default('0');
            $table->boolean('status_tanaman')->default('0'); //0-> tanaman_ada ,1->tanaman habis, 2->tanaman baru


            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('entry_id')->references('id')->on('EntryBST')->onDelete('cascade');
            // $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('cascade');


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
        Schema::dropIfExists('BST');
    }
};
