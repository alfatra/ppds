@extends('layouts.master')
@section('title')
	{{ isset($ppds) ? 'Edit Data PPDS' : 'Tambah Data PPDS' }}
@endsection
@section('content')
	<x-breadcrumb pagetitle="PPDS" title="{{ isset($ppds) ? 'Edit Data' : 'Tambah Data' }}" />

	<div class="row">
		<div class="col-lg-8 mx-auto">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">{{ isset($ppds) ? 'Formulir Edit Data PPDS' : 'Formulir Input Data PPDS' }}</h4>
					<p class="card-title-desc">Isi semua data yang diperlukan pada form di bawah ini.</p>
				</div>
				<div class="card-body p-4">

					{{-- Mengarahkan form ke route 'ppds.store' --}}
					<form action="{{ isset($ppds) ? route('ppds.update', $ppds->id) : route('ppds.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						@if(isset($ppds))
							@method('PUT')
						@endif

						<div class="mb-4">
							<label for="nama_lengkap" class="form-label">Nama Lengkap</label>
							<div class="input-group">
								<span class="input-group-text"><i class="ri-user-3-line"></i></span>
								<input class="form-control" type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $ppds->nama_lengkap ?? '') }}" required>
							</div>
						</div>

						<div class="mb-4">
							<label for="email" class="form-label">Email</label>
							<div class="input-group">
								<span class="input-group-text"><i class="ri-mail-line"></i></span>
								<input class="form-control" type="email" name="email" placeholder="contoh@email.com" id="email" value="{{ old('email', $ppds->email ?? '') }}" required>
							</div>
						</div>

						<div class="mb-4">
							<label for="telepon" class="form-label">Telepon</label>
							<div class="input-group">
								<span class="input-group-text"><i class="ri-phone-line"></i></span>
								<input class="form-control" type="tel" name="telepon" placeholder="081234567890" id="telepon" value="{{ old('telepon', $ppds->telepon ?? '') }}">
							</div>
						</div>

						<div class="mb-4">
							<label for="agama" class="form-label">Agama</label>
							<div class="input-group">
								<span class="input-group-text"><i class="ri-star-line"></i></span>
								<select class="form-select" name="agama" id="agama">
									<option>Pilih Agama</option>
									@php $selectedAgama = old('agama', $ppds->agama ?? ''); @endphp
									<option value="Islam" {{ $selectedAgama == 'Islam' ? 'selected' : '' }}>Islam</option>
									<option value="Kristen Protestan" {{ $selectedAgama == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
									<option value="Katolik" {{ $selectedAgama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
									<option value="Hindu" {{ $selectedAgama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
									<option value="Buddha" {{ $selectedAgama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
									<option value="Khonghucu" {{ $selectedAgama == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
								</select>
							</div>
						</div>

						<div class="mb-4">
							<label for="alamat" class="form-label">Alamat</label>
							<textarea id="alamat" class="form-control" name="alamat" rows="4" placeholder="Masukkan alamat lengkap">{{ old('alamat', $ppds->alamat ?? '') }}</textarea>
						</div>

						<hr class="my-4">

						<div class="mb-4">
							<label for="berkas" class="form-label">Upload Berkas</label>
							<input class="form-control" type="file" name="berkas" id="berkas" accept="image/png, image/jpeg, application/pdf, .doc, .docx">
							<div class="mt-2">
								<img id="berkas-preview" src="#" alt="Pratinjau Gambar" class="img-thumbnail" style="display: none; max-height: 200px;"/>
							</div>
							@if(isset($ppds) && $ppds->path_berkas)
								<div class="mt-2">
									<small>Berkas saat ini: 
										<a href="{{ Storage::url($ppds->path_berkas) }}" target="_blank" class="fw-medium">Lihat</a> | 
										<a href="{{ route('ppds.download', $ppds->id) }}" class="fw-medium">Download</a>
									</small>
								</div>
							@endif
							<div class="form-text">Tipe file yang diizinkan: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB.</div>
						</div>

						<div class="row mt-4">
							<div class="col-12 text-end">
								<a href="{{ route('ppds.index') }}" class="btn btn-outline-secondary w-md">Batal</a>
								<button type="submit" class="btn btn-primary w-md">{{ isset($ppds) ? 'Update' : 'Simpan' }}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div> <!-- end col -->
	</div>
	<!-- end row -->
@endsection
@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const berkasInput = document.getElementById('berkas');
        const berkasPreview = document.getElementById('berkas-preview');

        berkasInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                // Jika file adalah gambar, tampilkan pratinjau
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    berkasPreview.src = e.target.result;
                    berkasPreview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                // Jika bukan gambar atau tidak ada file, sembunyikan pratinjau
                berkasPreview.style.display = 'none';
            }
        });
    });
</script>
@endpush