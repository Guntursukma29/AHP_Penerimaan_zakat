<?php

namespace App\Http\Controllers;

use App\Helpers\ComparisonHelper;
use App\Models\Kriteria;
use App\Models\PerbandinganKriteria;
use App\Models\PerbandinganPekerjaan;
use App\Models\PerbandinganPenghasilan;
use App\Models\PerbandinganTempatTinggal;
use App\Models\PerbandinganKondisiKesehatan;
use App\Models\PerbandinganTanggunganKeluarga;
use App\Models\PenerimaanZakat;
use Illuminate\Http\Request;

class PerangkinganController extends Controller
{
    public function index()
    {
        $rataRataKriteria = $this->getRataRataKriteria();
        $hasilRataRata = $this->getHasilRataRata();

        $ranking = $this->calculateRanking($hasilRataRata, $rataRataKriteria);

        return view('hasil.perangkingan', [
            'hasilRataRata' => $hasilRataRata,
            'rataRataKriteria' => $rataRataKriteria,
            'ranking' => $ranking,
        ]);
    }

    private function getRataRataKriteria()
    {
        $kriteria = Kriteria::all();
        $size = $kriteria->count();
        $perbandinganKriteria = PerbandinganKriteria::all();

        $calculations = ComparisonHelper::calculateComparison($size, $kriteria, $perbandinganKriteria);
        $eigenVector = $calculations['eigenVector'];

        return $this->mapKriteriaToEigenVector($kriteria, $eigenVector);
    }

    private function mapKriteriaToEigenVector($kriteria, $eigenVector)
    {
        $rataRataKriteria = [];
        foreach ($kriteria as $key => $k) {
            $rataRataKriteria[$k->nama_kriteria] = $eigenVector[$key] ?? 0;
        }

        return $rataRataKriteria;
    }

    private function getHasilRataRata()
    {
        $penerimaZakat = PenerimaanZakat::all();
        $hasilRataRata = [];

        foreach ($penerimaZakat as $penerima) {
            $hasilRataRata[] = [
                'penerima' => $penerima->nama,
                'rata_pekerjaan' => $this->calculateRata('Pekerjaan', $penerima->id),
                'rata_penghasilan' => $this->calculateRata('Penghasilan', $penerima->id),
                'rata_tempattinggal' => $this->calculateRata('Tempat Tinggal', $penerima->id),
                'rata_kondisi_kesehatan' => $this->calculateRata('Kondisi Kesehatan', $penerima->id),
                'rata_tanggungan_keluarga' => $this->calculateRata('Tanggungan Keluarga', $penerima->id),
            ];
        }

        return $hasilRataRata;
    }

    private function calculateRata($type, $id)
    {
        $modelClass = 'App\\Models\\Perbandingan' . str_replace(' ', '', ucwords(strtolower($type)));
        $perbandingan = $modelClass::all();
        $size = PenerimaanZakat::count();

        $calculations = ComparisonHelper::calculateComparison($size, PenerimaanZakat::all(), $perbandingan);
        $eigenVector = $calculations['eigenVector'];

        return $eigenVector[$id] ?? 0;
    }

    private function calculateRanking($hasilRataRata, $rataRataKriteria)
    {
        $ranking = [];

        foreach ($hasilRataRata as $rata) {
            $total = (
                $rata['rata_pekerjaan'] * $rataRataKriteria['Pekerjaan'] +
                $rata['rata_penghasilan'] * $rataRataKriteria['Penghasilan'] +
                $rata['rata_tempattinggal'] * $rataRataKriteria['Tempat Tinggal'] +
                $rata['rata_kondisi_kesehatan'] * $rataRataKriteria['Kondisi Kesehatan'] +
                $rata['rata_tanggungan_keluarga'] * $rataRataKriteria['Tanggungan Keluarga']
            );

            $ranking[] = [
                'penerima' => $rata['penerima'],
                'total' => $total,
            ];
        }

        usort($ranking, fn($a, $b) => $b['total'] <=> $a['total']);

        return $ranking;
    }
}
