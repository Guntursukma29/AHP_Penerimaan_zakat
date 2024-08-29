@extends('layouts.template')

@section('content')
    <div class="card">
        <h5 class="card-header">Data Penerima Zakat</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataPenerimaZakat as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->nik }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>
                                <!-- Trigger Modal -->
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#detailsModal-{{ $data->id }}">
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Details -->
    @foreach ($dataPenerimaZakat as $data)
        <div class="modal fade" id="detailsModal-{{ $data->id }}" tabindex="-1" role="dialog"
            aria-labelledby="detailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailsModalLabel">Detail Penerima Zakat - {{ $data->nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('alternatif.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="data_penerima_zakat_id" value="{{ $data->id }}">

                            <!-- Form Group untuk menampilkan data -->
                            <div class="mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" value="{{ $data->nik }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" value="{{ $data->nama }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" value="{{ $data->alamat }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="asnaf" class="form-label">Asnaf</label>
                                <input type="text" class="form-control" id="asnaf" value="{{ $data->asnaf }}"
                                    readonly>
                            </div>

                            <!-- Form Group untuk Kriteria dan Sub Kriteria -->
                            @foreach ($kriteria as $kriterium)
                                <div class="mb-3">
                                    <label for="kriteria-{{ $kriterium->id }}"
                                        class="form-label">{{ $kriterium->nama_kriteria }}</label>
                                    <select name="sub_kriteria[{{ $kriterium->id }}]" id="kriteria-{{ $kriterium->id }}"
                                        class="form-control">
                                        <option value="">Select Sub Kriteria</option>
                                        @foreach ($kriterium->subKriteria as $sub)
                                            <option value="{{ $sub->id }}"
                                                {{ $data->subKriteria->firstWhere('id', $sub->id) ? 'selected' : '' }}>
                                                {{ $sub->sub_kriteria_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
