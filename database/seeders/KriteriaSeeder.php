<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kriteria = [
            'Pekerjaan',
            'Penghasilan',
            'Tempat Tinggal',
            'Tanggungan Keluarga',
            'Kondisi Kesehatan'
        ];

        foreach ($kriteria as $namaKriteria) {
            Kriteria::create(['nama_kriteria' => $namaKriteria]);
        }
    }
}
