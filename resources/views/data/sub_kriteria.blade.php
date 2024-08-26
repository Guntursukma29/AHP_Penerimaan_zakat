@extends('layouts.template')

@section('content')
    <div class="container">
        <h1>Sub Kriteria</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @foreach ($kriteria as $k)
            <div class="card mb-3">
                <div class="row">
                    <div class="col">
                        <h5 class="card-header">{{ $k->nama_kriteria }}</h5>
                    </div>
                    <div class="col text-end mt-3 mr-3">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                            data-bs-target="#addSubKriteriaModal-{{ $k->id }}">
                            Add Sub Kriteria
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sub Kriteria Name</th>
                                <th>Nilai</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($k->subKriteria as $index => $sub)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sub->sub_kriteria_name }}</td>
                                    <td>{{ $sub->nilai }}</td>
                                    <td>
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editSubKriteriaModal-{{ $sub->id }}">
                                            Edit
                                        </button>

                                        <!-- Edit Sub Kriteria Modal -->
                                        <div class="modal fade" id="editSubKriteriaModal-{{ $sub->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editSubKriteriaLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editSubKriteriaLabel">Edit Sub
                                                            Kriteria
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('subkriteria.update', $sub->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="form-group">
                                                                <label for="sub_kriteria_name">Sub Kriteria Name</label>
                                                                <input type="text" name="sub_kriteria_name"
                                                                    class="form-control"
                                                                    value="{{ $sub->sub_kriteria_name }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="nilai">Nilai</label>
                                                                <input type="number" name="nilai" class="form-control"
                                                                    value="{{ $sub->nilai }}" required>
                                                            </div>
                                                            <button type="submit" class="btn btn-warning">Update Sub
                                                                Kriteria</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Form -->
                                        <form action="{{ route('subkriteria.destroy', $sub->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <!-- Add Sub Kriteria Modal -->
                    <div class="modal fade" id="addSubKriteriaModal-{{ $k->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="addSubKriteriaLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSubKriteriaLabel">Add Sub Kriteria for
                                        {{ $k->nama_kriteria }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('subkriteria.store', $k->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="sub_kriteria_name">Sub Kriteria Name</label>
                                            <input type="text" name="sub_kriteria_name" class="form-control"
                                                placeholder="Sub Kriteria Name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nilai">Nilai</label>
                                            <input type="number" name="nilai" class="form-control" placeholder="Nilai"
                                                required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Sub Kriteria</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
