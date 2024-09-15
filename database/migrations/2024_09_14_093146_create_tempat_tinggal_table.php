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
        Schema::create('tempat_tinggal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kriteria1_id');
            $table->unsignedBigInteger('kriteria2_id');
            $table->unsignedBigInteger('selected_kriteria_id');
            $table->decimal('nilai', 8, 2);
            $table->timestamps();

            $table->foreign('kriteria1_id')->references('id')->on('data_penerima_zakat')->onDelete('cascade');
            $table->foreign('kriteria2_id')->references('id')->on('data_penerima_zakat')->onDelete('cascade');
            $table->foreign('selected_kriteria_id')->references('id')->on('data_penerima_zakat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_tinggal');
    }
};
