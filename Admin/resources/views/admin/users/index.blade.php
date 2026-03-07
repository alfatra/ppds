@extends('layouts.master')

@section('title')
    Manajemen Pengguna
@endsection

@section('content')
    <x-breadcrumb pagetitle="Admin" title="Manajemen Pengguna" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Pengguna Sistem</h4>
                    <p class="card-title-desc">Kelola role, status aktivasi, dan hapus akun pengguna.</p>

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

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                                            {{ ucfirst($role) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if ($user->is_active)
                                                    <button type="submit" class="btn btn-sm btn-success">Aktif</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-danger">Tidak Aktif</button>
                                                @endif
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteConfirmation({{ $user->id }})">
                                                <i class="mdi mdi-trash-can"></i>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function showDeleteConfirmation(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus akun ini?',
                text: "Tindakan ini permanen dan tidak dapat dibatalkan!",
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