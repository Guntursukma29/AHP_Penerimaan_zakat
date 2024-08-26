@extends('layouts.template')

@section('content')
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#zakatModal">
        Tambah Penerimaan Zakat
    </button>
    <br>
    <div class="card">
        <h5 class="card-header">Data Penerimaan Zakat</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penerima Zakat</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penerimaanZakat as $index => $penerima)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $penerima->nama }}</td>
                            <td>{{ $penerima->asnaf }}</td>
                            <td>
                                <!-- Button Edit -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $penerima->id }}">
                                    <i class="bx bx-edit-alt"></i> Edit
                                </button>

                                <!-- Button Delete -->
                                <form action="{{ route('penerimaan-zakat.destroy', $penerima->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr> <!-- Modal Edit -->
                        <div class="modal fade" id="editModal{{ $penerima->id }}" tabindex="-1"
                            aria-labelledby="editModalLabel{{ $penerima->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $penerima->id }}">Edit Penerimaan Zakat
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form Edit -->
                                        <form action="{{ route('penerimaan-zakat.update', $penerima->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <!-- NIK -->
                                            <div class="mb-3">
                                                <label for="nik" class="form-label">NIK</label>
                                                <input type="text" class="form-control" id="nik" name="nik"
                                                    value="{{ old('nik', $penerima->nik) }}" required>
                                            </div>

                                            <!-- Nama -->
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    value="{{ old('nama', $penerima->nama) }}" required>
                                            </div>

                                            <!-- Alamat -->
                                            <div class="mb-3">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $penerima->alamat) }}</textarea>
                                            </div>

                                            <!-- Asnaf -->
                                            <div class="mb-3">
                                                <label for="asnaf" class="form-label">Asnaf</label>
                                                <select class="form-control" id="asnaf" name="asnaf" required>
                                                    <option value="fakir"
                                                        {{ old('asnaf', $penerima->asnaf) == 'fakir' ? 'selected' : '' }}>
                                                        Fakir</option>
                                                    <option value="miskin"
                                                        {{ old('asnaf', $penerima->asnaf) == 'miskin' ? 'selected' : '' }}>
                                                        Miskin</option>
                                                    <option value="amil"
                                                        {{ old('asnaf', $penerima->asnaf) == 'amil' ? 'selected' : '' }}>
                                                        Amil</option>
                                                    <option value="mualaf"
                                                        {{ old('asnaf', $penerima->asnaf) == 'mualaf' ? 'selected' : '' }}>
                                                        Mualaf</option>
                                                    <option value="gharim"
                                                        {{ old('asnaf', $penerima->asnaf) == 'gharim' ? 'selected' : '' }}>
                                                        Gharim</option>
                                                    <option value="fisabilillah"
                                                        {{ old('asnaf', $penerima->asnaf) == 'fisabilillah' ? 'selected' : '' }}>
                                                        Fisabilillah</option>
                                                    <option value="ibnu_sabil"
                                                        {{ old('asnaf', $penerima->asnaf) == 'ibnu_sabil' ? 'selected' : '' }}>
                                                        Ibnu Sabil</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="zakatModal" tabindex="-1" aria-labelledby="zakatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zakatModalLabel">Form Penerimaan Zakat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('penerimaan-zakat.store') }}" method="POST">
                        @csrf
                        <!-- Form Input Fields Here -->
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="asnaf" class="form-label">Asnaf</label>
                            <select class="form-control" id="asnaf" name="asnaf" required>
                                <option value="fakir">Fakir</option>
                                <option value="miskin">Miskin</option>
                                <option value="amil">Amil</option>
                                <option value="mualaf">Mualaf</option>
                                <option value="gharim">Gharim</option>
                                <option value="fisabilillah">Fisabilillah</option>
                                <option value="ibnu_sabil">Ibnu Sabil</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOtherPekerjaan() {
            var pekerjaan = document.getElementById('pekerjaan');
            var pekerjaanLainnya = document.getElementById('pekerjaan_lainnya');
            if (pekerjaan.value === 'lainnya') {
                pekerjaanLainnya.style.display = 'block';
            } else {
                pekerjaanLainnya.style.display = 'none';
            }
        }
    </script>
@endsection
