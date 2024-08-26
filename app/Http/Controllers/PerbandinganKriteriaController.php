<?php
namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class PerbandinganKriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::all();
        return view('p_kriteria.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $kriteria = Kriteria::all();
        $jumlahKriteria = count($kriteria);

        if ($jumlahKriteria < 2) {
            return back()->with('error', 'Jumlah kriteria tidak cukup untuk perhitungan.');
        }

        // Initialize comparison matrix
        $comparisonMatrix = array_fill(0, $jumlahKriteria, array_fill(0, $jumlahKriteria, 1));

        // Process input comparison values from the form
        if ($request->has('nilai') && is_array($request->input('nilai'))) {
            foreach ($request->input('nilai') as $i => $row) {
                foreach ($row as $j => $nilai) {
                    if (isset($nilai) && $nilai > 0) {
                        $comparisonMatrix[$i][$j] = $nilai;
                        $comparisonMatrix[$j][$i] = 1 / $nilai;
                    }
                }
            }
        }

        // Calculate column sums
        $columnSums = array_fill(0, $jumlahKriteria, 0);
        for ($j = 0; $j < $jumlahKriteria; $j++) {
            for ($i = 0; $i < $jumlahKriteria; $i++) {
                $columnSums[$j] += $comparisonMatrix[$i][$j] ?? 0;
            }
        }

        // Normalize matrix
        $normalizedMatrix = [];
        for ($i = 0; $i < $jumlahKriteria; $i++) {
            for ($j = 0; $j < $jumlahKriteria; $j++) {
                $normalizedMatrix[$i][$j] = $columnSums[$j] > 0 ? ($comparisonMatrix[$i][$j] ?? 0) / $columnSums[$j] : 0;
            }
        }

        // Calculate criteria weights
        $criteriaWeights = [];
        for ($i = 0; $i < $jumlahKriteria; $i++) {
            $criteriaWeights[$i] = array_sum($normalizedMatrix[$i]) / $jumlahKriteria;
        }

        // Calculate consistency ratio
        $lambdaMax = array_sum(array_map(function ($row, $weight) {
            return array_sum(array_map(function ($value) use ($weight) {
                return $value * $weight;
            }, $row)) / array_sum($row);
        }, $normalizedMatrix, $criteriaWeights)) / $jumlahKriteria;

        $ci = ($lambdaMax - $jumlahKriteria) / ($jumlahKriteria - 1);
        $ri = $this->getRI($jumlahKriteria);
        $cr = $ri ? $ci / $ri : 0;

        // Store results in session
        session()->put('comparison_matrix', $comparisonMatrix);
        session()->put('normalized_matrix', $normalizedMatrix);
        session()->put('criteria_weights', $criteriaWeights);
        session()->put('lambda_max', $lambdaMax);
        session()->put('ci', $ci);
        session()->put('cr', $cr);
        session()->put('jumlah_kriteria', $jumlahKriteria); // Save jumlahKriteria to session

        return redirect()->route('perbandingan-kriteria.result');
    }

    public function result()
    {
        $comparisonMatrix = session('comparison_matrix', []);
        $normalizedMatrix = session('normalized_matrix', []);
        $criteriaWeights = session('criteria_weights', []);
        $lambdaMax = session('lambda_max', 0);
        $ci = session('ci', 0);
        $cr = session('cr', 0);
        $kriteria = Kriteria::all();
        $jumlahKriteria = session('jumlah_kriteria', 0);
    
        // Initialize column sums and priority vectors
        $columnSums = array_fill(0, $jumlahKriteria, 0);
        $priorityVectors = array_fill(0, $jumlahKriteria, 0);
    
        // Ensure normalizedMatrix is not null
        if (is_array($normalizedMatrix) && !empty($normalizedMatrix)) {
            $rows = count($normalizedMatrix);
            $cols = count($normalizedMatrix[0]);
    
            for ($j = 0; $j < $cols; $j++) {
                $columnSum = 0;
                for ($i = 0; $i < $rows; $i++) {
                    $columnSum += $normalizedMatrix[$i][$j] ?? 0;
                }
                $columnSums[$j] = $columnSum;
            }
    
            // Calculate priority vectors
            foreach ($criteriaWeights as $i => $weightSum) {
                $priorityVectors[$i] = isset($columnSums[$i]) && $columnSums[$i] > 0 ? $weightSum / $columnSums[$i] : 0;
            }
        }
    
        return view('p_kriteria.result', compact('kriteria', 'comparisonMatrix', 'normalizedMatrix', 'criteriaWeights', 'lambdaMax', 'ci', 'cr', 'columnSums', 'priorityVectors'));
    }

    private function getRI($n)
    {
        $ri = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        return $ri[$n] ?? 0;
    }
}
