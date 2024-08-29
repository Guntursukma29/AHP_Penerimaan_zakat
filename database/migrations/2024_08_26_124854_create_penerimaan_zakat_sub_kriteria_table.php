<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenerimaanZakatSubKriteriaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penerimaan_zakat_sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_zakat_id')->constrained('data_penerima_zakat')->onDelete('cascade');
            $table->foreignId('sub_kriteria_id')->constrained('sub_kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria'); // Add this line
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_zakat_sub_kriteria');
    }
}
