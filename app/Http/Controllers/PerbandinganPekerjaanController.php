<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\PenerimaanZakat;
use App\Helpers\ComparisonHelper;
use App\Models\PerbandinganPekerjaan;

class PerbandinganPekerjaanController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('subKriteria')->where('nama_kriteria', 'Pekerjaan')->first();
        $penerimaZakat = PenerimaanZakat::all();
        $size = $penerimaZakat->count();
        $perbandinganPekerjaan = PerbandinganPekerjaan::all();

        $comparisonResults = ComparisonHelper::calculateComparison($size, $penerimaZakat, $perbandinganPekerjaan);

        // Ambil hasil perhitungan dari helper
        $matrix = $comparisonResults['matrix'];
        $columnTotals = $comparisonResults['columnTotals'];
        $normalizedMatrix = $comparisonResults['normalizedMatrix'];
        $eigenVector = $comparisonResults['eigenVector'];
        $lambdaMax = $comparisonResults['lambdaMax'];
        $ci = $comparisonResults['ci'];
        $cr = $comparisonResults['cr'];

        $perbandinganArray = ComparisonHelper::getPerbandinganArray($perbandinganPekerjaan);

        return view('p_alternatif.pekerjaan', compact(
            'penerimaZakat',
            'matrix',
            'columnTotals',
            'normalizedMatrix',
            'eigenVector',
            'lambdaMax',
            'ci',
            'cr',
            'eigenVector',
            'perbandinganArray',
            'kriteria',
        ));
    }

    public function store(Request $request)
    {
        foreach ($request->kriteria as $kriteria1Id => $kriteriaPairs) {
            foreach ($kriteriaPairs as $kriteria2Id => $selectedKriteriaId) {
                $nilai = $request->nilai[$kriteria1Id][$kriteria2Id];
                $existingComparison = PerbandinganPekerjaan::where('kriteria1_id', $kriteria1Id)
                    ->where('kriteria2_id', $kriteria2Id)
                    ->first();

                if ($existingComparison) {
                    $existingComparison->update([
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                } else {
                    PerbandinganPekerjaan::create([
                        'kriteria1_id' => $kriteria1Id,
                        'kriteria2_id' => $kriteria2Id,
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Perbandingan pekerjaan berhasil disimpan.');
    }
  
}
