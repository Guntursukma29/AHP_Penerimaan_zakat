@extends('layouts.template')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="text-center mt-2 mb-4">Matriks Perbandingan Berpasangan</h5>
                <div class="table-responsive">
                    <!-- Matriks Perbandingan Berpasangan -->
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kriteria</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $i => $k1)
                            <tr>
                                <td class="font-weight-bold">{{ $k1->nama_kriteria }}</td>
                                @foreach ($kriteria as $j => $k2)
                                    @php
                                        $value = $comparisonMatrix[$i][$j] ?? 0;
                                    @endphp
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                            
                            <!-- Jumlah Row -->
                            <tr class="font-weight-bold">
                                <td>Jumlah</td>
                                {{-- @foreach ($columnSums as $sum)
                                    <td>{{$sum}}</td>
                                @endforeach --}}
                            </tr>
                        </tbody>
                    </table>
                    

                    <!-- Matriks Nilai Kriteria -->
                    <h5 class="text-center mt-4 mb-4">Matriks Nilai Kriteria</h5>
                    <table class="table table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kriteria</th>
                                @foreach ($kriteria as $k)
                                    <th>{{ $k->nama_kriteria }}</th>
                                @endforeach
                                <th>Jumlah</th>
                                <th>Vektor Prioritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $i => $k1)
                                <tr>
                                    <td class="font-weight-bold">{{ $k1->nama_kriteria }}</td>
                                    @foreach ($normalizedMatrix[$i] ?? [] as $value)
                                        <td>{{ number_format($value, 5) }}</td>
                                    @endforeach
                                    <td>{{ number_format($criteriaWeightSums[$i] ?? 0, 5) }}</td>
                                    <td>{{ number_format($priorityVectors[$i] ?? 0, 5) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Konsistensi dan Informasi Eigenvalue -->
                    <div class="mt-4">
                        <p><strong>Prinsip Eigen Vektor (Lambda Max):</strong> {{ number_format($lambdaMax, 5) }}</p>
                        <p><strong>Indeks Konsistensi:</strong> {{ number_format($ci, 5) }}</p>
                        <p><strong>Rasio Konsistensi:</strong> {{ number_format($cr, 5) }}</p>
                    </div>

                    <!-- Pesan Peringatan -->
                    @if ($cr > 0.1)
                        <div class="alert alert-danger text-center" role="alert">
                            <strong>Nilai Rasio Konsistensi melebihi 10%</strong><br>
                            Silakan input kembali tabel perbandingan.
                        </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="text-center">
                        <a href="{{ route('perbandingankriteria.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
