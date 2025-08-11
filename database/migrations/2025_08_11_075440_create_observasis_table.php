<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('observasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('kelas_id');
            $table->integer('pertanyaan_1')->nullable();
            $table->integer('pertanyaan_2')->nullable();
            $table->integer('pertanyaan_3')->nullable();
            $table->integer('pertanyaan_4')->nullable();
            $table->integer('pertanyaan_5')->nullable();
            $table->integer('pertanyaan_6')->nullable();
            $table->integer('pertanyaan_7')->nullable();
            $table->integer('total')->nullable();
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            // Add any additional fields as necessary
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observasis');
    }
};
