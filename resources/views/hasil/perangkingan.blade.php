@extends('layouts.template')

@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-semibold text-center mb-4">Rata-rata Nilai Per Alternatif di Semua Kriteria</h4>
            <div class="table-responsive">
                
    <a href="{{ route('cetak.pdf') }}" class="btn btn-primary mb-4" target="_blank">Cetak PDF</a>

                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
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
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="table-responsive">
                        <h4 class="fw-semibold text-center mt-4">Rata-rata Kriteria</h4>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Nama Kriteria</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rataRataKriteria as $kriteria => $rata)
                                    <tr>
                                        <td>{{ $kriteria }}</td>
                                        <td>{{ number_format($rata, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="table-responsive">
                        <h4 class="fw-semibold text-center mt-4">Total Skor Penerima Zakat</h4>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
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
                                            'Pekerjaan' =>
                                                round($rata['rata_pekerjaan'], 2) *
                                                round($rataRataKriteria['Pekerjaan'], 2),
                                            'Penghasilan' =>
                                                round($rata['rata_penghasilan'], 2) *
                                                round($rataRataKriteria['Penghasilan'], 2),
                                            'Tempat Tinggal' =>
                                                round($rata['rata_tempattinggal'], 2) *
                                                round($rataRataKriteria['Tempat Tinggal'], 2),
                                            'Kondisi Kesehatan' =>
                                                round($rata['rata_kondisi_kesehatan'], 2) *
                                                round($rataRataKriteria['Kondisi Kesehatan'], 2),
                                            'Tanggungan Keluarga' =>
                                                round($rata['rata_tanggungan_keluarga'], 2) *
                                                round($rataRataKriteria['Tanggungan Keluarga'], 2),
                                        ];
                                        $total = array_sum($detailPerhitungan);
                                    @endphp

                                    <tr>
                                        <td>{{ $rata['penerima'] }}</td>
                                        <td>
                                            @foreach ($detailPerhitungan as $kriteria => $nilai)
                                                {{ $kriteria }}: {{ number_format($nilai, 4) }}<br>
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($total, 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="table-responsive">
                        <h4 class="fw-semibold text-center mt-4">Ranking Penerima Zakat</h4>
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
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
                                        <td>{{ number_format($rank['total'], 4) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    @endsection
