@extends('layouts.template')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center">Hasil Perhitungan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>overall composite haight</th>
                    <th>priority vektor</th>
                    @foreach ($penerimaanZakat as $penerima)
                        <th>{{ $penerima->nama }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Pekerjaan</td>
                    <td>{{ $pekerjaanResult['priority_vektor'] }}</td>
                    @foreach ($penerimaanZakat as $penerima)
                        <td>{{ $pekerjaanResult[$penerima->id] ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Penghasilan</td>
                    <td>{{ $penghasilanResult['priority_vektor'] }}</td>
                    @foreach ($penerimaanZakat as $penerima)
                        <td>{{ $penghasilanResult[$penerima->id] ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Tempat Tinggal</td>
                    <td>{{ $tempatTinggalResult['priority_vektor'] }}</td>
                    @foreach ($penerimaanZakat as $penerima)
                        <td>{{ $tempatTinggalResult[$penerima->id] ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Tanggungan Keluarga</td>
                    <td>{{ $tanggunganKeluargaResult['priority_vektor'] }}</td>
                    @foreach ($penerimaanZakat as $penerima)
                        <td>{{ $tanggunganKeluargaResult[$penerima->id] ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Kondisi Kesehatan</td>
                    <td>{{ $kondisiKesehatanResult['priority_vektor'] }}</td>
                    @foreach ($penerimaanZakat as $penerima)
                        <td>{{ $kondisiKesehatanResult[$penerima->id] ?? '-' }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>TOTAL</td>
                    <td>-</td>
                    @foreach ($ranking as $rank)
                        <td>{{ $rank['total_nilai'] }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>

        <h2 class="text-center">Perangkingan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Alternatif</th>
                    <th>Nilai</th>
                    <th>Peringkat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ranking as $key => $rank)
                    <tr>
                        <td>{{ $rank['nama'] }}</td>
                        <td>{{ number_format($rank['total_nilai'], 3) }}</td>
                        <td>{{ $key + 1 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
