<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\PenerimaanZakat;
use App\Models\PerbandinganPenghasilan;

class PerbandinganPenghasilanController extends Controller
{
    public function index()
    {
        // Ambil kriteria "Pekerjaan" beserta sub-kriteria
        $kriteria = Kriteria::with('subKriteria')->where('nama_kriteria', 'Pekerjaan')->first();
        
        // Ambil data penerimaan zakat
        $penerimaanzakat = PenerimaanZakat::all();


        $size = $penerimaanzakat->count();  
        $matrix = $this->initializeMatrix($size);  
        $columnTotals = array_fill(0, $size, 0);
        $perbandinganPenghasilan = PerbandinganPenghasilan::all();

        // Mengisi matriks dan total kolom
        $this->fillMatrixAndColumnTotals($perbandinganPenghasilan, $matrix, $columnTotals);

        foreach ($columnTotals as $index => $total) {
            if ($total == 0) {
                $columnTotals[$index] = 1;
            }
        }

        // Hitung matriks normalisasi
        $normalizedMatrix = $this->calculateNormalizedMatrix($matrix, $columnTotals, $size);
        $eigenVector = $this->calculateEigenVector($normalizedMatrix, $size);
        $lambdaMax = $this->calculateLambdaMax($columnTotals, $eigenVector, $size);
        $sumEigenVector = array_sum($eigenVector);

        $ci = ($lambdaMax - $size) / ($size - 1);
        $ir = 1.12; // Nilai Random Index untuk ukuran matriks 4-6
        $cr = $ci / $ir;

        // Ambil data perbandingan untuk form
        $perbandinganArray = $this->getPerbandinganArray($perbandinganPenghasilan);

        return view('p_alternatif.penghasilan', compact('penerimaanzakat', 'matrix', 'columnTotals', 'normalizedMatrix', 'eigenVector', 'lambdaMax', 'ci', 'cr', 'sumEigenVector', 'perbandinganArray', 'kriteria'));
    }

    public function store(Request $request)
    {
        foreach ($request->kriteria as $kriteria1Id => $kriteriaPairs) {
            foreach ($kriteriaPairs as $kriteria2Id => $selectedKriteriaId) {
                $nilai = $request->nilai[$kriteria1Id][$kriteria2Id];

                $existingComparison = PerbandinganPenghasilan::where('kriteria1_id', $kriteria1Id)
                    ->where('kriteria2_id', $kriteria2Id)
                    ->first();

                if ($existingComparison) {
                    $existingComparison->update([
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                } else {
                    PerbandinganPenghasilan::create([
                        'kriteria1_id' => $kriteria1Id,
                        'kriteria2_id' => $kriteria2Id,
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Perbandingan kriteria berhasil disimpan.');
    }

    private function initializeMatrix($size)
    {
        return array_fill(0, $size, array_fill(0, $size, 1));
    }

    private function fillMatrixAndColumnTotals($perbandinganPenghasilan, &$matrix, &$columnTotals)
    {
        foreach ($perbandinganPenghasilan as $perbandingan) {
            $i = $perbandingan->kriteria1_id - 1;
            $j = $perbandingan->kriteria2_id - 1;

            if ($perbandingan->selected_kriteria_id == $perbandingan->kriteria1_id) {
                $matrix[$i][$j] = round($perbandingan->nilai, 2);
                $matrix[$j][$i] = round(1 / $perbandingan->nilai, 2);
            } else {
                $matrix[$i][$j] = round(1 / $perbandingan->nilai, 2);
                $matrix[$j][$i] = round($perbandingan->nilai, 2);
            }

            $columnTotals[$j] += $matrix[$i][$j];
        }
    }

    private function calculateNormalizedMatrix($matrix, $columnTotals, $size)
    {
        $normalizedMatrix = array_fill(0, $size, array_fill(0, $size, 0));
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                if ($columnTotals[$j] != 0) {
                    $normalizedMatrix[$i][$j] = round($matrix[$i][$j] / $columnTotals[$j], 7);
                }
            }
        }
        return $normalizedMatrix;
    }

    private function calculateEigenVector($normalizedMatrix, $size)
    {
        $eigenVector = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $eigenVector[$i] = round($rowSum / $size, 7);
        }
        return $eigenVector;
    }

    private function calculateLambdaMax($columnTotals, $eigenVector, $size)
    {
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            $lambdaMax += round($columnTotals[$i], 2) * round($eigenVector[$i], 5);
        }
        return $lambdaMax;
    }

    private function getPerbandinganArray($perbandinganPenghasilan)
    {
        $perbandinganArray = [];
        foreach ($perbandinganPenghasilan as $perbandingan) {
            $key = $perbandingan->kriteria1_id . '-' . $perbandingan->kriteria2_id;
            $perbandinganArray[$key] = $perbandingan;
        }
        return $perbandinganArray;
    }
}
