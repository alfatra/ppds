@extends('layouts.master')
@section('title')
	{{ isset($ppds) ? 'Edit Data PPDS' : 'Tambah Data PPDS' }}
@endsection
@section('content')
	<x-breadcrumb pagetitle="PPDS" title="{{ isset($ppds) ? 'Edit Data' : 'Tambah Data' }}" />

	<div class="row">
		<div class="col-xl-8 mx-auto">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2 align-middle font-size-18"></i>
                    <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali form anda.
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

			<form action="{{ isset($ppds) ? route('ppds.update', $ppds->id) : route('ppds.store') }}" method="POST" enctype="multipart/form-data">
				@csrf
				@if(isset($ppds))
					@method('PUT')
				@endif

				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-transparent border-bottom px-4 py-3">
						<h5 class="card-title mb-0">
                            <i class="ri-user-settings-line me-2 text-primary"></i> 
                            {{ isset($ppds) ? 'Edit Informasi Pribadi' : 'Informasi Pribadi PPDS' }}
                        </h5>
					</div>
					<div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nama_lengkap" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-user-3-line text-muted"></i></span>
                                    <input class="form-control @error('nama_lengkap') is-invalid @enderror" type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap (beserta gelar)" id="nama_lengkap" value="{{ old('nama_lengkap', $ppds->nama_lengkap ?? '') }}" required>
                                </div>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-medium">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-mail-line text-muted"></i></span>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="contoh@rs.com" id="email" value="{{ old('email', $ppds->email ?? '') }}" required>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="telepon" class="form-label fw-medium">No. WhatsApp / Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-phone-line text-muted"></i></span>
                                    <input class="form-control" type="tel" name="telepon" placeholder="081234567890" id="telepon" value="{{ old('telepon', $ppds->telepon ?? '') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="agama" class="form-label fw-medium">Agama</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="ri-star-line text-muted"></i></span>
                                    <select class="form-select" name="agama" id="agama">
                                        <option value="">-- Pilih Agama --</option>
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

                            <div class="col-12 mb-2">
                                <label for="alamat" class="form-label fw-medium">Alamat Domisili</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light align-items-start pt-2"><i class="ri-map-pin-line text-muted"></i></span>
                                    <textarea id="alamat" class="form-control" name="alamat" rows="3" placeholder="Masukkan alamat domisili saat ini secara lengkap">{{ old('alamat', $ppds->alamat ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
					</div>
				</div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom px-4 py-3">
						<h5 class="card-title mb-0">
                            <i class="ri-folder-user-line me-2 text-primary"></i> 
                            Dokumen Pendukung
                        </h5>
					</div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="berkas" class="form-label fw-medium">Upload Berkas Pendukung</label>
                                <input class="form-control" type="file" name="berkas" id="berkas" accept="application/pdf, .doc, .docx">
                                <div class="form-text text-muted mb-3"><i class="ri-information-line me-1"></i>Tipe file: PDF, DOC, DOCX. Maksimal 5MB.</div>
                                
                                @if(isset($ppds) && $ppds->path_berkas)
                                    <div class="mt-3 p-3 bg-light rounded border border-dashed text-center">
                                        <div class="mb-2">
                                            <i class="ri-file-text-line display-6 text-primary"></i>
                                        </div>
                                        <h6 class="font-size-14 mb-1">Berkas Tersimpan</h6>
                                        <div class="d-flex justify-content-center gap-2 mt-3">
                                            <a href="{{ Storage::url($ppds->path_berkas) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ri-eye-line me-1"></i> Lihat Berkas</a>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3 border-start">
                                <label for="foto_profil" class="form-label fw-medium">Pasfoto 3x4</label>
                                <input class="form-control mb-2" type="file" name="foto_profil" id="foto_profil" accept="image/png, image/jpeg, image/jpg">
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="gunakan_foto_profil" id="gunakan_foto_profil" value="1">
                                    <label class="form-check-label text-muted font-size-13" for="gunakan_foto_profil">
                                        Gunakan foto profil akun saya saat ini
                                    </label>
                                </div>
                                <div class="form-text text-muted mb-3"><i class="ri-information-line me-1"></i>Tipe file: JPG, PNG. Maksimal 2MB.</div>
                                
                                <div class="mt-2 text-center bg-light rounded border border-dashed p-3" id="preview-container" style="display: none;">
                                    <p class="text-muted mb-2 font-size-13">Pratinjau Foto:</p>
                                    <img id="foto_profil-preview" src="#" alt="Pratinjau Foto" class="img-thumbnail shadow-sm" style="max-height: 180px; object-fit: cover; aspect-ratio: 3/4;"/>
                                </div>
                                
                                @if(isset($ppds) && $ppds->foto_profil)
                                    <div class="mt-3 p-3 bg-light rounded border border-dashed text-center" id="saved-foto-container">
                                        <h6 class="font-size-14 mb-3">Pasfoto Tersimpan</h6>
                                        <img src="{{ str_contains($ppds->foto_profil, 'http') ? $ppds->foto_profil : Storage::url($ppds->foto_profil) }}" class="img-thumbnail shadow-sm" style="max-height: 180px; object-fit: cover; aspect-ratio: 3/4;" alt="Pasfoto">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('ppds.index') }}" class="btn btn-light waves-effect shadow-sm">
                        <i class="ri-arrow-go-back-line me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary waves-effect waves-light shadow-sm" id="btn-submit">
                        <i class="ri-save-3-line me-1"></i> {{ isset($ppds) ? 'Simpan Perubahan' : 'Simpan Data' }}
                    </button>
                </div>
			</form>
		</div> <!-- end col -->
	</div>
	<!-- end row -->
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fotoInput = document.getElementById('foto_profil');
        const fotoPreview = document.getElementById('foto_profil-preview');
        const previewContainer = document.getElementById('preview-container');
        const checkboxGunakanFoto = document.getElementById('gunakan_foto_profil');
        const savedFotoContainer = document.getElementById('saved-foto-container');

        // Handle foto input change
        if(fotoInput) {
            fotoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fotoPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                        if(savedFotoContainer) savedFotoContainer.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                    
                    // Uncheck checkbox
                    if(checkboxGunakanFoto) checkboxGunakanFoto.checked = false;
                } else {
                    previewContainer.style.display = 'none';
                    if(savedFotoContainer) savedFotoContainer.style.display = 'block';
                }
            });
        }

        // Handle checkbox change
        if(checkboxGunakanFoto) {
            checkboxGunakanFoto.addEventListener('change', function() {
                if(this.checked) {
                    fotoInput.value = ''; // clear file input
                    previewContainer.style.display = 'none';
                    if(savedFotoContainer) savedFotoContainer.style.display = 'block';
                }
            });
        }

        // SweetAlert untuk notifikasi sukses jika ada session success
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#5664d2',
                confirmButtonText: 'OK'
            });
        @endif

        // Tambahkan efek loading saat form disubmit
        document.querySelector('form').addEventListener('submit', function() {
            let btn = document.getElementById('btn-submit');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
            btn.classList.add('disabled');
        });
    });
</script>
@endpush
