@extends('layouts.template')

@section('content')
    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-semibold text-center mb-4">Rata-rata Nilai Per Alternatif di Semua Kriteria</h4>
            <div class="table-responsive">
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
                                    <th>Total Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasilRataRata as $rata)
                                    @php
                                        $total =
                                            $rata['rata_pekerjaan'] * $rataRataKriteria['Pekerjaan'] +
                                            $rata['rata_penghasilan'] * $rataRataKriteria['Penghasilan'] +
                                            $rata['rata_tempattinggal'] * $rataRataKriteria['Tempat Tinggal'] +
                                            $rata['rata_kondisi_kesehatan'] * $rataRataKriteria['Kondisi Kesehatan'] +
                                            $rata['rata_tanggungan_keluarga'] *
                                                $rataRataKriteria['Tanggungan Keluarga'];
                                    @endphp
                                    <tr>
                                        <td>{{ $rata['penerima'] }}</td>
                                        <td>{{ number_format($total, 2) }}</td>
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
                                        <td>{{ number_format($rank['total'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    @endsection
