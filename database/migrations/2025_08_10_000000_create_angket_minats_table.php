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
        Schema::create('angket_minat', function (Blueprint $table) {
            $table->id();
            $table->integer('siswa_id');
            $table->integer('kelas_id');
            $table->tinyInteger('pertanyaan_1')->default(1);
            $table->tinyInteger('pertanyaan_2')->default(1);
            $table->tinyInteger('pertanyaan_3')->default(1);
            $table->tinyInteger('pertanyaan_4')->default(1);
            $table->tinyInteger('pertanyaan_5')->default(1);
            $table->tinyInteger('pertanyaan_6')->default(1);
            $table->tinyInteger('pertanyaan_7')->default(1);
            $table->tinyInteger('pertanyaan_8')->default(1);
            $table->tinyInteger('pertanyaan_9')->default(1);
            $table->tinyInteger('pertanyaan_10')->default(1);
            $table->tinyInteger('pertanyaan_11')->default(1);
            $table->tinyInteger('pertanyaan_12')->default(1);
            $table->tinyInteger('pertanyaan_13')->default(1);
            $table->tinyInteger('pertanyaan_14')->default(1);
            $table->tinyInteger('total')->default(14);
            $table->timestamps();

            // Indexes for better performance
            $table->index('siswa_id');
            $table->index('kelas_id');
            $table->index('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('angket_minat');
    }
};
