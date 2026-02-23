@extends('layouts.master')

@section('title', 'Manajemen Data PPDS')

@section('content')

    {{-- Judul Halaman --}}
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Data PPDS</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('root') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data PPDS</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Data</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ganti $ppds_items dengan variabel data Anda --}}
                    @foreach ($ppds_items as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>
                                {{-- Cek role user --}}
                                @if (auth()->user()->role === \App\Models\User::ROLE_USER)
                                    {{-- Jika role adalah 'user', tombol akan memicu SweetAlert --}}
                                    <button type="button" class="btn btn-sm btn-info" onclick="showAccessDeniedAlert()">Edit</button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showAccessDeniedAlert()">Hapus</button>
                                @else
                                    {{-- Jika role adalah 'admin' atau 'superadmin', tombol berfungsi normal --}}
                                    <a href="{{ route('ppds.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deletePpds({{ $item->id }})">Hapus</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan layout utama Anda memiliki @stack('scripts') sebelum </body> --}}
    <script>
        function showAccessDeniedAlert() {
            // Menampilkan SweetAlert
            Swal.fire({
                title: 'Akses Ditolak!',
                text: 'Anda tidak memiliki akses untuk melakukan aksi ini.',
                icon: 'warning',
                confirmButtonColor: '#f46a6a',
                buttonsStyling: true
            });
        }

        function deletePpds(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Apakah anda yakin ingin menghapus data ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f46a6a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke delete endpoint
                    window.location.href = "{{ route('ppds.destroy', '') }}/" + id;
                }
            });
        }
    </script>
@endpush