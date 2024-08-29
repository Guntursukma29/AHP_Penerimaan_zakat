<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subKriteriaData = [
            'Pekerjaan' => [
                ['sub_kriteria_name' => 'PNS', 'nilai' => 3],
                ['sub_kriteria_name' => 'Swasta', 'nilai' => 2],
                ['sub_kriteria_name' => 'Wiraswasta', 'nilai' => 1],
            ],
            'Penghasilan' => [
                ['sub_kriteria_name' => '< 2 juta', 'nilai' => 3],
                ['sub_kriteria_name' => '2-5 juta', 'nilai' => 2],
                ['sub_kriteria_name' => '> 5 juta', 'nilai' => 1],
            ],
            'Kondisi Kesehatan' => [
                ['sub_kriteria_name' => 'Sehat', 'nilai' => 1],
                ['sub_kriteria_name' => 'Sakit Ringan', 'nilai' => 2],
                ['sub_kriteria_name' => 'Sakit Berat', 'nilai' => 3],
            ],
            'Tempat Tinggal' => [
                ['sub_kriteria_name' => 'Milik Sendiri', 'nilai' => 1],
                ['sub_kriteria_name' => 'Sewa/Kontrak', 'nilai' => 2],
                ['sub_kriteria_name' => 'Tinggal dengan Orang Lain', 'nilai' => 3],
            ],
            'Tanggungan Keluarga' => [
                ['sub_kriteria_name' => '1-2 Orang', 'nilai' => 1],
                ['sub_kriteria_name' => '3-4 Orang', 'nilai' => 2],
                ['sub_kriteria_name' => '> 4 Orang', 'nilai' => 3],
            ],
        ];

        foreach ($subKriteriaData as $kriteria => $subKriteriaItems) {
            $kriteriaModel = Kriteria::where('nama_kriteria', $kriteria)->first();

            foreach ($subKriteriaItems as $subKriteria) {
                SubKriteria::create([
                    'kriteria_id' => $kriteriaModel->id,
                    'sub_kriteria_name' => $subKriteria['sub_kriteria_name'],
                    'nilai' => $subKriteria['nilai'],
                ]);
            }
        }
    }
}
