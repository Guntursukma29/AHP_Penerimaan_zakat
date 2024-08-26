@extends('layouts.template')

@section('content')
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createKriteriaModal">Tambah
        Kriteria</button>
    <div class="card">
        <h5 class="card-header">Kriteria</h5>
        <div class="table-responsive text-nowrap">
            <table class="table ">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama Kriteria</th>
                        <th>Nilai</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriteria as $index => $kriterium)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $kriterium->nama_kriteria }}</td>
                            <td>{{ $kriterium->nilai }}</td>
                            <td>
                                <div class="d-flex">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal"
                                        data-bs-target="#editKriteriaModal-{{ $kriterium->id }}">
                                        Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('kriteria.destroy', $kriterium->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editKriteriaModal-{{ $kriterium->id }}" tabindex="-1"
                            aria-labelledby="editKriteriaLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editKriteriaLabel">Edit Kriteria</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('kriteria.update', $kriterium->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="nama_kriteria">Nama Kriteria</label>
                                                <input type="text" name="nama_kriteria" class="form-control"
                                                    value="{{ $kriterium->nama_kriteria }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="nilai">Nilai</label>
                                                <input type="number" name="nilai" class="form-control"
                                                    value="{{ $kriterium->nilai }}" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Kriteria</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal for creating kriteria -->
            <!-- Modal -->
            <div class="modal fade" id="createKriteriaModal" tabindex="-1" aria-labelledby="createKriteriaModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('kriteria.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addKriteriaModalLabel">Tambah Kriteria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                                    <input type="text" class="form-control" id="nama_kriteria" name="nama_kriteria"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="nilai" class="form-label">Nama Kriteria</label>
                                    <input type="number" class="form-control" id="nilai"
                                        name="nilai"placeholder="Nilai" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function deleteKriteria(id) {
                if (confirm('Are you sure you want to delete this kriteria?')) {
                    document.getElementById('deleteKriteriaForm' + id).submit();
                }
            }
        </script>
    </div>
@endsection
