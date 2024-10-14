<?php

namespace App\Http\Controllers;

use App\Helpers\ComparisonHelper;
use App\Models\Kriteria;
use App\Models\PerbandinganKriteria;
use App\Models\PenerimaanZakat;
use Barryvdh\DomPDF\Facade\Pdf;

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

        foreach ($penerimaZakat as $index => $penerima) {
            $hasilRataRata[] = [
                'penerima' => $penerima->nama,
                'rata_pekerjaan' => $this->calculateRata('Pekerjaan', $index),
                'rata_penghasilan' => $this->calculateRata('Penghasilan', $index),
                'rata_tempattinggal' => $this->calculateRata('TempatTinggal', $index),
                'rata_kondisi_kesehatan' => $this->calculateRata('KondisiKesehatan', $index),
                'rata_tanggungan_keluarga' => $this->calculateRata('TanggunganKeluarga', $index),
            ];
        }

        return $hasilRataRata;
    }

    private function calculateRata($type, $penerimaZakatIndex)
    {
        $modelClass = 'App\\Models\\Perbandingan' . str_replace(' ', '', ucwords(strtolower($type)));
        $perbandingan = $modelClass::all();
        $size = PenerimaanZakat::count();

        $calculations = ComparisonHelper::calculateComparison($size, PenerimaanZakat::all(), $perbandingan);
        $eigenVector = $calculations['eigenVector'];

        return $eigenVector[$penerimaZakatIndex] ?? 0;
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
    public function generatePDF()
    {
        $rataRataKriteria = $this->getRataRataKriteria();
        $hasilRataRata = $this->getHasilRataRata();
        $ranking = $this->calculateRanking($hasilRataRata, $rataRataKriteria);

        $pdf = PDF::loadView('hasil.pdf', [
            'hasilRataRata' => $hasilRataRata,
            'rataRataKriteria' => $rataRataKriteria,
            'ranking' => $ranking,
        ]);

        return $pdf->download('laporan_penerima_zakat.pdf');
    }
}
