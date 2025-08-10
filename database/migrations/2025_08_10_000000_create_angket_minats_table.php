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
            $table->string('nama');
            $table->string('kelas');
            $table->tinyInteger('nilai_1')->default(1);
            $table->tinyInteger('nilai_2')->default(1);
            $table->tinyInteger('nilai_3')->default(1);
            $table->tinyInteger('nilai_4')->default(1);
            $table->tinyInteger('nilai_5')->default(1);
            $table->tinyInteger('nilai_6')->default(1);
            $table->tinyInteger('nilai_7')->default(1);
            $table->tinyInteger('nilai_8')->default(1);
            $table->tinyInteger('nilai_9')->default(1);
            $table->tinyInteger('nilai_10')->default(1);
            $table->tinyInteger('nilai_11')->default(1);
            $table->tinyInteger('nilai_12')->default(1);
            $table->tinyInteger('nilai_13')->default(1);
            $table->tinyInteger('nilai_14')->default(1);
            $table->tinyInteger('total_nilai')->default(14);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('nama');
            $table->index('kelas');
            $table->index('total_nilai');
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