<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerbandinganPekerjaan;
use App\Models\PerbandinganPenghasilan;
use App\Models\PerbandinganTempatTinggal;
use App\Models\PerbandinganKondisiKesehatan;
use App\Models\PerbandinganTanggunganKeluarga;
use App\Models\PenerimaanZakat;

class PerangkinganController extends Controller
{
    public function index()
    {
        // Mengambil data dari setiap tabel
        $pekerjaan = PerbandinganPekerjaan::all();
        $penghasilan = PerbandinganPenghasilan::all();
        $tempatTinggal = PerbandinganTempatTinggal::all();
        $tanggunganKeluarga = PerbandinganTanggunganKeluarga::all();
        $kondisiKesehatan = PerbandinganKondisiKesehatan::all();
        
        // Mengambil data penerimaan zakat
        $penerimaanZakat = PenerimaanZakat::all();

        // Perhitungan AHP untuk setiap tabel
        $pekerjaanResult = $this->calculateAHP($pekerjaan);
        $penghasilanResult = $this->calculateAHP($penghasilan);
        $tempatTinggalResult = $this->calculateAHP($tempatTinggal);
        $tanggunganKeluargaResult = $this->calculateAHP($tanggunganKeluarga);
        $kondisiKesehatanResult = $this->calculateAHP($kondisiKesehatan);

        // Menyusun hasil perangkingan dengan data penerima zakat
        $ranking = $this->calculateRanking(
            $pekerjaanResult,
            $penghasilanResult,
            $tempatTinggalResult,
            $tanggunganKeluargaResult,
            $kondisiKesehatanResult,
            $penerimaanZakat
        );

        // Return hasil ke view
        return view('hasil.perangkingan', compact(
            'pekerjaanResult',
            'penghasilanResult',
            'tempatTinggalResult',
            'tanggunganKeluargaResult',
            'kondisiKesehatanResult',
            'ranking',
            'penerimaanZakat'
        ));
    }

    // Fungsi untuk melakukan perhitungan AHP
    private function calculateAHP($comparisons)
{
    $matrix = [];
    $total = [];

    // Membuat matriks perbandingan
    foreach ($comparisons as $comparison) {
        $matrix[$comparison->kriteria1_id][$comparison->kriteria2_id] = $comparison->nilai;
    }

    // Menambahkan jumlah kolom di setiap baris untuk normalisasi
    foreach ($matrix as $kriteria => $values) {
        $total[$kriteria] = array_sum($values);
    }

    // Normalisasi matriks
    foreach ($matrix as $kriteria => $values) {
        foreach ($values as $k2 => $value) {
            $matrix[$kriteria][$k2] = $value / $total[$k2];
        }
    }

    // Hitung rata-rata untuk mendapatkan prioritas
    $priority = [];
    foreach ($matrix as $kriteria => $values) {
        $priority[$kriteria] = array_sum($values) / count($values);
    }

    // Tambahkan nilai prioritas untuk setiap penerima zakat (memastikan semua ID penerima zakat ada)
    $result = [];
    foreach ($comparisons as $comparison) {
        $result[$comparison->penerima_id] = $priority[$comparison->kriteria1_id] ?? 0;
    }

    return $result;
}


    // Fungsi untuk menghitung ranking berdasarkan hasil AHP
    private function calculateRanking($pekerjaanResult, $penghasilanResult, $tempatTinggalResult, $tanggunganKeluargaResult, $kondisiKesehatanResult, $penerimaanZakat)
    {
        $ranking = [];

        foreach ($penerimaanZakat as $penerima) {
            $ranking[$penerima->id] = [
                'nama' => $penerima->nama,
                'total_nilai' => (
                    ($pekerjaanResult[$penerima->id] ?? 0) +
                    ($penghasilanResult[$penerima->id] ?? 0) +
                    ($tempatTinggalResult[$penerima->id] ?? 0) +
                    ($tanggunganKeluargaResult[$penerima->id] ?? 0) +
                    ($kondisiKesehatanResult[$penerima->id] ?? 0)
                )
            ];
        }

        // Urutkan berdasarkan total nilai (ranking tertinggi di atas)
        usort($ranking, function ($a, $b) {
            return $b['total_nilai'] <=> $a['total_nilai'];
        });

        return $ranking;
    }
}
