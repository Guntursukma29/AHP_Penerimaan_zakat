<?php
namespace App\Helpers;

use App\Models\PenerimaanZakat;
use App\Models\PerbandinganPekerjaan;

class ComparisonHelper
{
    public static function calculateComparison($size, $penerimaanZakat, $perbandinganPekerjaan)
    {
        $matrix = self::initializeMatrix($size);
        $columnTotals = array_fill(0, $size, 0);
        $idToIndex = self::mapIdsToIndices($penerimaanZakat);

        self::fillMatrixAndColumnTotals($perbandinganPekerjaan, $matrix, $columnTotals, $idToIndex);

        foreach ($columnTotals as $index => $total) {
            if ($total == 0) {
                $columnTotals[$index] = 1;
            }
        }

        $normalizedMatrix = self::calculateNormalizedMatrix($matrix, $columnTotals, $size);
        $eigenVector = self::calculateEigenVector($normalizedMatrix, $size);
        $lambdaMax = self::calculateLambdaMax($columnTotals, $eigenVector, $size);
        $ci = ($lambdaMax - $size) / ($size - 1);
        $cr = $ci / 1.12; // Adjust based on your needs

        return compact('matrix', 'columnTotals', 'normalizedMatrix', 'eigenVector', 'lambdaMax', 'ci', 'cr');
    }

    public static function getPerbandinganArray($perbandinganPekerjaan)
    {
        $perbandinganArray = [];
        foreach ($perbandinganPekerjaan as $perbandingan) {
            $key = $perbandingan->kriteria1_id . '-' . $perbandingan->kriteria2_id;
            $perbandinganArray[$key] = $perbandingan;
        }
        return $perbandinganArray;
    }

    private static function initializeMatrix($size)
    {
        return array_fill(0, $size, array_fill(0, $size, 1));
    }

    private static function fillMatrixAndColumnTotals($perbandinganPekerjaan, &$matrix, &$columnTotals, $idToIndex)
    {
        foreach ($perbandinganPekerjaan as $perbandingan) {
            if (!isset($idToIndex[$perbandingan->kriteria1_id]) || !isset($idToIndex[$perbandingan->kriteria2_id])) {
                continue;
            }
            $i = $idToIndex[$perbandingan->kriteria1_id];
            $j = $idToIndex[$perbandingan->kriteria2_id];

            if ($perbandingan->selected_kriteria_id == $perbandingan->kriteria1_id) {
                $matrix[$i][$j] = round($perbandingan->nilai, 2);
                $matrix[$j][$i] = round(1 / $perbandingan->nilai, 2);
            } else {
                $matrix[$i][$j] = round(1 / $perbandingan->nilai, 2);
                $matrix[$j][$i] = round($perbandingan->nilai, 2);
            }

            $columnTotals[$j] += $matrix[$i][$j];
            $columnTotals[$i] += $matrix[$j][$i];
        }
        foreach ($idToIndex as $index) {
            $matrix[$index][$index] = 1;
            $columnTotals[$index] += 1; 
        }

        foreach ($columnTotals as $index => $total) {
            if ($total == 0) {
                $columnTotals[$index] = 1;
            }
        }
    }

    private static function calculateNormalizedMatrix($matrix, $columnTotals, $size)
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

    private static function calculateEigenVector($normalizedMatrix, $size)
    {
        $eigenVector = array_fill(0, $size, 0);
        for ($i = 0; $i < $size; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $eigenVector[$i] = round($rowSum / $size, 7);
        }
        return $eigenVector;
    }

    private static function calculateLambdaMax($columnTotals, $eigenVector, $size)
    {
        $lambdaMax = 0;
        for ($i = 0; $i < $size; $i++) {
            $lambdaMax += round($columnTotals[$i], 2) * round($eigenVector[$i], 5);
        }
        return $lambdaMax;
    }

    private static function mapIdsToIndices($penerimaanZakat)
    {
        $idToIndex = [];
        foreach ($penerimaanZakat as $index => $pz) {
            $idToIndex[$pz->id] = $index;
        }
        return $idToIndex;
    }
}
