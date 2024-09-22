<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;
use App\Models\PenerimaanZakat;
use App\Models\PerbandinganKondisiKesehatan;
use App\Helpers\ComparisonHelper;

class PerbandinganKondisiKesehatanController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::with('subKriteria')->where('nama_kriteria', 'KondisiKesehatan')->first();
        $penerimaZakat = PenerimaanZakat::all();
        $size = $penerimaZakat->count();
        $perbandinganKondisiKesehatan = PerbandinganKondisiKesehatan::all();

        $calculations = ComparisonHelper::calculateComparison($size, $penerimaZakat, $perbandinganKondisiKesehatan);

        $matrix = $calculations['matrix'];
        $columnTotals = $calculations['columnTotals'];
        $normalizedMatrix = $calculations['normalizedMatrix'];
        $eigenVector = $calculations['eigenVector'];
        $lambdaMax = $calculations['lambdaMax'];
        $ci = $calculations['ci'];
        $cr = $calculations['cr'];

        $sumEigenVector = array_sum($eigenVector);

        $perbandinganArray = ComparisonHelper::getPerbandinganArray($perbandinganKondisiKesehatan);

        return view('p_alternatif.KondisiKesehatan', compact(
            'penerimaZakat',
            'matrix',
            'columnTotals',
            'normalizedMatrix',
            'eigenVector',
            'lambdaMax',
            'ci',
            'cr',
            'sumEigenVector',
            'perbandinganArray',
            'kriteria',
            'perbandinganKondisiKesehatan',
        ));
    }

    public function store(Request $request)
    {
        foreach ($request->kriteria as $kriteria1Id => $kriteriaPairs) {
            foreach ($kriteriaPairs as $kriteria2Id => $selectedKriteriaId) {
                $nilai = $request->nilai[$kriteria1Id][$kriteria2Id];
                $existingComparison = PerbandinganKondisiKesehatan::where('kriteria1_id', $kriteria1Id)
                    ->where('kriteria2_id', $kriteria2Id)
                    ->first();

                if ($existingComparison) {
                    $existingComparison->update([
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                } else {
                    PerbandinganKondisiKesehatan::create([
                        'kriteria1_id' => $kriteria1Id,
                        'kriteria2_id' => $kriteria2Id,
                        'selected_kriteria_id' => $selectedKriteriaId,
                        'nilai' => $nilai,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Perbandingan KondisiKesehatan berhasil disimpan.');
    }
}
