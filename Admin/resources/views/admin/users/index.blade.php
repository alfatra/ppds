@extends('layouts.master')

@section('title', 'Manajemen Pengguna')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Manajemen Pengguna</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengguna</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
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
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                @method('PATCH')
                                                <select name="role" class="form-select form-select-sm">
                                                    @foreach ($roles as $role)
                                                        {{-- Hanya Superadmin yang bisa melihat/memberi role Superadmin --}}
                                                        @if ($role === \App\Models\User::ROLE_SUPERADMIN && !Auth::user()->isSuperAdmin())
                                                            @continue
                                                        @endif
                                                        <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                                            {{ ucfirst($role) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            @if ($user->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="d-inline form-confirm-action">
                                                @csrf
                                                @method('PATCH')
                                                @if ($user->is_active)
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Nonaktifkan">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Aktifkan">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('script')
    <!-- Sweet-Alert  -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Konfirmasi untuk Aktifkan/Nonaktifkan ---
            document.querySelectorAll('.form-confirm-action').forEach(form => {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Mencegah form submit secara langsung

                    const button = this.querySelector('button[type="submit"]');
                    const isActivating = button.classList.contains('btn-outline-success');
                    const actionText = isActivating ? 'mengaktifkan' : 'menonaktifkan';
                    const verbText = isActivating ? 'Aktifkan' : 'Nonaktifkan';
                    const userName = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim();

                    Swal.fire({
                        title: 'Konfirmasi Tindakan',
                        html: `Apakah Anda yakin ingin <b>${actionText}</b> pengguna <br> "<b>${userName}</b>"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: isActivating ? '#28a745' : '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: `Ya, ${verbText}!`,
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit(); // Jika dikonfirmasi, lanjutkan submit form
                        }
                    });
                });
            });

            // --- Konfirmasi untuk Perubahan Role ---
            let previousRoleIndex;
            document.querySelectorAll('select[name="role"]').forEach(select => {
                select.addEventListener('focus', function () {
                    previousRoleIndex = this.selectedIndex;
                });

                select.addEventListener('change', function (event) {
                    const form = this.form;
                    const userName = this.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
                    const newRole = this.options[this.selectedIndex].text.trim();

                    Swal.fire({
                        title: 'Konfirmasi Perubahan Role',
                        html: `Ubah role untuk "<b>${userName}</b>" menjadi "<b>${newRole}</b>"?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#556ee6',
                        cancelButtonColor: '#74788d',
                        confirmButtonText: 'Ya, Ubah!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        } else {
                            // Jika dibatalkan, kembalikan pilihan ke nilai semula
                            this.selectedIndex = previousRoleIndex;
                        }
                    });
                });
            });
        });
    </script>
@endsection