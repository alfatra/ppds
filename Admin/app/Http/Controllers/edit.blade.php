@extends('layouts.app') <!-- Sesuaikan dengan nama layout master admin/template Anda -->

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Lengkapi Profil Pengguna</h4>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $ppds->nama_lengkap ?? $user->name) }}" required>
                            @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email (Tidak bisa diubah)</label>
                            <input type="email" class="form-control" id="email" value="{{ $user->email }}" readonly disabled>
                        </div>

                        <div class="mb-3">
                            <label for="telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" value="{{ old('telepon', $ppds->telepon ?? '') }}">
                            @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="agama" class="form-label">Agama</label>
                            <input type="text" class="form-control @error('agama') is-invalid @enderror" id="agama" name="agama" value="{{ old('agama', $ppds->agama ?? '') }}">
                            @error('agama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $ppds->alamat ?? '') }}</textarea>
                            @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="berkas" class="form-label">Upload Berkas Pendukung (PDF/Doc/Image)</label>
                            <input type="file" class="form-control @error('berkas') is-invalid @enderror" id="berkas" name="berkas">
                            @if(isset($ppds) && $ppds->path_berkas)
                                <small class="text-muted mt-2 d-block">
                                    Berkas saat ini: <a href="{{ Storage::url($ppds->path_berkas) }}" target="_blank">Lihat Berkas</a>
                                </small>
                            @endif
                            @error('berkas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Profil & Data PPDS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection