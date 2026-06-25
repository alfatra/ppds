@extends('layouts.master')

@section('title')
    Master Tindakan Medis
@endsection

@section('content')
    <x-breadcrumb pagetitle="Admin" title="Master Tindakan Medis" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title">Daftar Tindakan Medis</h4>
                            <p class="card-title-desc">Kelola data master tindakan medis untuk logbook harian.</p>
                        </div>
                        <a href="{{ route('admin.medical-activities.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i> Tambah Tindakan Medis
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Tindakan</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $key => $activity)
                                    <tr>
                                        <td>{{ $activities->firstItem() + $key }}</td>
                                        <td>{{ $activity->name }}</td>
                                        <td>{{ $activity->description ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.medical-activities.edit', $activity->id) }}" class="btn btn-sm btn-info">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteConfirmation({{ $activity->id }})">
                                                <i class="mdi mdi-trash-can"></i> Hapus
                                            </button>
                                            <form id="delete-form-{{ $activity->id }}" action="{{ route('admin.medical-activities.destroy', $activity->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data tindakan medis.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showDeleteConfirmation(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus tindakan ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endpush
