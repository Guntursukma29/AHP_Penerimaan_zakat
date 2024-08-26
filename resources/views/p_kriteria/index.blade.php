@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="card">
            <h5 class="text-center mt-4">PERBANDINGAN KRITERIA</h5>
            <div class="table-responsive text-nowrap">
                <form action="{{ url('/submit-perbandingan') }}" method="POST">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kriteria Perbandingan</th>
                                <th>Nilai Perbandingan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $k1)
                                @foreach ($kriteria as $k2)
                                    @if ($k1->id < $k2->id)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="kriteria[{{ $k1->id }}][{{ $k2->id }}]"
                                                        value="{{ $k1->id }}">
                                                    <label class="form-check-label">{{ $k1->nama_kriteria }}</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="kriteria[{{ $k1->id }}][{{ $k2->id }}]"
                                                        value="{{ $k2->id }}">
                                                    <label class="form-check-label">{{ $k2->nama_kriteria }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="nilai[{{ $k1->id }}][{{ $k2->id }}]"
                                                    class="form-control" required>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-secondary">SUBMIT</button>
                        <a href="{{ route('lanjut') }}" class="btn btn-primary">Lanjut >></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
