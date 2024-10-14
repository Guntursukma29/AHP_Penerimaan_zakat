<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perangkingan Penerima Zakat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        h4 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h4>Laporan Perangkingan Penerima Zakat</h4>
    <h5>Rata-rata Nilai Per Alternatif di Semua Kriteria</h5>
    <table>
        <thead>
            <tr>
                <th>Nama Penerima</th>
                <th>Rata-rata Pekerjaan</th>
                <th>Rata-rata Penghasilan</th>
                <th>Rata-rata Tempat Tinggal</th>
                <th>Rata-rata Kondisi Kesehatan</th>
                <th>Rata-rata Tanggungan Keluarga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilRataRata as $rata)
                <tr>
                    <td>{{ $rata['penerima'] }}</td>
                    <td>{{ number_format($rata['rata_pekerjaan'], 2) }}</td>
                    <td>{{ number_format($rata['rata_penghasilan'], 2) }}</td>
                    <td>{{ number_format($rata['rata_tempattinggal'], 2) }}</td>
                    <td>{{ number_format($rata['rata_kondisi_kesehatan'], 2) }}</td>
                    <td>{{ number_format($rata['rata_tanggungan_keluarga'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Bagian Total Skor Penerima Zakat -->
    <h5>Total Skor Penerima Zakat</h5>
    <table>
        <thead>
            <tr>
                <th>Nama Penerima</th>
                <th>Detail Perhitungan</th>
                <th>Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasilRataRata as $rata)
                @php
                    $detailPerhitungan = [
                        'Pekerjaan' => round($rata['rata_pekerjaan'], 2) * round($rataRataKriteria['Pekerjaan'], 2),
                        'Penghasilan' => round($rata['rata_penghasilan'], 2) * round($rataRataKriteria['Penghasilan'], 2),
                        'Tempat Tinggal' => round($rata['rata_tempattinggal'], 2) * round($rataRataKriteria['Tempat Tinggal'], 2),
                        'Kondisi Kesehatan' => round($rata['rata_kondisi_kesehatan'], 2) * round($rataRataKriteria['Kondisi Kesehatan'], 2),
                        'Tanggungan Keluarga' => round($rata['rata_tanggungan_keluarga'], 2) * round($rataRataKriteria['Tanggungan Keluarga'], 2),
                    ];
                    $total = array_sum($detailPerhitungan);
                @endphp
                <tr>
                    <td>{{ $rata['penerima'] }}</td>
                    <td>
                        @foreach ($detailPerhitungan as $kriteria => $nilai)
                            {{ $kriteria }}: {{ number_format($nilai, 2) }}<br>
                        @endforeach
                    </td>
                    <td>{{ number_format($total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h5>Ranking Penerima Zakat</h5>
    <table>
        <thead>
            <tr>
                <th>Rangking</th>
                <th>Nama Penerima</th>
                <th>Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ranking as $rank)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rank['penerima'] }}</td>
                    <td>{{ number_format($rank['total'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
