@extends('layouts.master')
@section('title', 'Daftar Mahasiswa Bimbingan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Daftar Mahasiswa Bimbingan</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="text-muted font-size-14 mb-4">
                    Daftar mahasiswa PPDS yang menjadi tanggung jawab Anda sebagai DPJP/Supervisor.
                </p>
                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Agama</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswa as $mhs)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $mhs->getProfilePhotoUrl() }}" alt="foto" class="rounded-circle avatar-sm" style="object-fit: cover;">
                            </td>
                            <td>{{ $mhs->name }}</td>
                            <td>{{ $mhs->email }}</td>
                            <td>{{ optional($mhs->ppds)->telepon ?? '-' }}</td>
                            <td>{{ optional($mhs->ppds)->agama ?? '-' }}</td>
                            <td>{{ optional($mhs->ppds)->alamat ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada mahasiswa bimbingan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
