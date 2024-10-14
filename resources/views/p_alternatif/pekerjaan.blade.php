@extends('layouts.template')

@section('content')
    <div class="card">
        <h5 class="text-center mt-4">PERBANDINGAN PEKERJAAN</h5>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <form action="{{ url('/submit-perbandinganpekerjaan') }}" method="POST">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" colspan="2">Kriteria Perbandingan</th>
                                <th>Nilai Perbandingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penerimaZakat as $k1)
                                @foreach ($penerimaZakat as $k2)
                                    @if ($k1->id < $k2->id)
                                        @php
                                            $key = $k1->id . '-' . $k2->id;
                                            $existingComparison = $perbandinganArray[$key] ?? null;
                                            $selectedKriteria = $existingComparison
                                                ? $existingComparison->selected_kriteria_id
                                                : null;
                                            $nilai = $existingComparison ? $existingComparison->nilai : null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="kriteria[{{ $k1->id }}][{{ $k2->id }}]"
                                                        value="{{ $k1->id }}"
                                                        {{ $selectedKriteria == $k1->id ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ $k1->nama }} <br>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="kriteria[{{ $k1->id }}][{{ $k2->id }}]"
                                                        value="{{ $k2->id }}"
                                                        {{ $selectedKriteria == $k2->id ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ $k2->nama }}

                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="nilai[{{ $k1->id }}][{{ $k2->id }}]"
                                                    class="form-control" value="{{ number_format($nilai, 0) }}">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RESULT --}}
    <div class="card my-3">
        <h5 class="text-center mt-4">Matriks Perbandingan Berpasangan</h5>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        @foreach ($penerimaZakat as $k)
                            <th>{{ $k->nama }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penerimaZakat as $i => $k1)
                        <tr>
                            <td>{{ $k1->nama }}</td>
                            @foreach ($penerimaZakat as $j => $k2)
                                <td>
                                    @if (intval($matrix[$i][$j]) == $matrix[$i][$j])
                                        {{ intval($matrix[$i][$j]) }} 
                                    @else
                                        {{ number_format($matrix[$i][$j], 2) }} 
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        @foreach ($columnTotals as $total)
                            <td><strong>{{ number_format($total, 2) }} </strong></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <div class="card my-3">
        <h5 class="text-center mt-4">Normalisasi </h5>
        <div class="card-body">
            <table class="table table-bordered my-3">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        @foreach ($penerimaZakat as $krit)
                            <th>{{ $krit->nama }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($normalizedMatrix as $i => $row)
                        <tr>
                            <td>{{ $penerimaZakat[$i]->nama }}</td>
                            @foreach ($row as $j => $value)
                                <td>{{ number_format($value, 6) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5>Rata Rata (Prioritas Bobot)</h5>
            <ul>
                @foreach ($eigenVector as $index => $eigen)
                    <li>{{ $penerimaZakat[$index]->nama }}: {{ number_format($eigen, 6) }}</li>
                @endforeach
            </ul>
            <p><strong>Total : {{ number_format(array_sum($eigenVector), 6) }}</strong></p>
            <table class="table mt-4">
                <tr>
                    <td>Lambda Max</td>
                    <td>{{ number_format($lambdaMax, 6) }}</td>
                </tr>
                <tr>
                    <td>CI</td>
                    <td>{{ number_format($ci, 6) }}</td>
                </tr>
                <tr>
                    <td>CR</td>
                    <td>{{ number_format($cr, 6) }}</td>
                </tr>
            </table>
        </div>
    </div>
    </div>
@endsection
