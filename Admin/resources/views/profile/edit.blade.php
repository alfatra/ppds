@extends('layouts.master')
@section('title')
Profil Saya
@endsection
@section('content')
<x-breadcrumb pagetitle="Profil" title="Edit Profil Saya" />

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Formulir Edit Profil</h4>
                <p class="card-title-desc">Update informasi profil Anda di bawah ini. Data ini juga akan tersimpan di Data PPDS.</p>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Informasi Akun User (Read-only) -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-2"><i class="ri-information-line"></i> Informasi Akun</h6>
                        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-0"><strong>Bergabung sejak:</strong> {{ $user->created_at->format('d M Y') }}</p>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-user-3-line"></i></span>
                            <input class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                   type="text" 
                                   name="nama_lengkap" 
                                   placeholder="Masukkan nama lengkap" 
                                   id="nama_lengkap" 
                                   value="{{ old('nama_lengkap', $ppds->nama_lengkap ?? $user->name ?? '') }}" 
                                   required>
                            @error('nama_lengkap')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Telepon -->
                    <div class="mb-4">
                        <label for="telepon" class="form-label">Nomor Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                            <input class="form-control @error('telepon') is-invalid @enderror" 
                                   type="tel" 
                                   name="telepon" 
                                   placeholder="081234567890" 
                                   id="telepon" 
                                   value="{{ old('telepon', $ppds->telepon ?? '') }}">
                            @error('telepon')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Agama -->
                    <div class="mb-4">
                        <label for="agama" class="form-label">Agama</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ri-star-line"></i></span>
                            <select class="form-select @error('agama') is-invalid @enderror" 
                                    name="agama" 
                                    id="agama">
                                <option value="">Pilih Agama</option>
                                @php $selectedAgama = old('agama', $ppds->agama ?? ''); @endphp
                                <option value="Islam" {{ $selectedAgama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen Protestan" {{ $selectedAgama == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                <option value="Katolik" {{ $selectedAgama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ $selectedAgama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ $selectedAgama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Khonghucu" {{ $selectedAgama == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
                            </select>
                            @error('agama')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea id="alamat" 
                                  class="form-control @error('alamat') is-invalid @enderror" 
                                  name="alamat" 
                                  rows="4" 
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $ppds->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <!-- Foto Profil Upload -->
                    <div class="mb-4">
                        <label for="foto_profil" class="form-label">Foto Profil <span class="text-danger">*</span> (Ukuran 3x4)</label>
                        <p class="text-muted small mb-2"><i class="ri-information-line"></i> Foto profil harus memiliki aspek rasio 3:4 (misal: 300x400, 600x800 px)</p>
                        
                        <div class="row">
                            <!-- Preview Area -->
                            <div class="col-md-4 mb-3 text-center">
                                <div class="card border-2" style="border-color: #e3e6f0;">
                                    <div class="card-body p-0" style="aspect-ratio: 3/4; overflow: hidden; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        @if($ppds && $ppds->foto_profil)
                                            <img id="foto-profil-preview" 
                                                 src="{{ Storage::url($ppds->foto_profil) }}" 
                                                 alt="Foto Profil Saat Ini" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="text-center text-muted">
                                                <i class="ri-image-add-line" style="font-size: 3rem;"></i>
                                                <p class="mt-2">Preview Foto</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($ppds && $ppds->foto_profil)
                                    <small class="text-success mt-2 d-block">Foto profil saat ini</small>
                                @endif
                            </div>

                            <!-- Upload Input -->
                            <div class="col-md-8">
                                <input class="form-control @error('foto_profil') is-invalid @enderror" 
                                       type="file" 
                                       name="foto_profil" 
                                       id="foto_profil" 
                                       accept="image/png, image/jpeg"
                                       aria-label="Upload foto profil">
                                <div class="form-text">Format: JPG, PNG | Minimal 300x400 px | Maksimal 2MB</div>
                                
                                @error('foto_profil')
                                    <div class="invalid-feedback d-block">
                                        <i class="ri-error-warning-line"></i> {{ $message }}
                                    </div>
                                @endif

                                <!-- File Info Display -->
                                <div id="foto-info" class="mt-3 p-2 bg-light rounded" style="display: none;">
                                    <small>
                                        <p class="mb-1"><strong>Informasi File:</strong></p>
                                        <p class="mb-1">Ukuran: <span id="file-size">-</span></p>
                                        <p class="mb-1">Dimensi: <span id="file-dimensions">-</span></p>
                                        <p class="mb-0">Rasio: <span id="aspect-ratio" class="badge bg-info">-</span></p>
                                    </small>
                                </div>

                                <!-- Validation Message -->
                                <div id="aspect-ratio-warning" class="alert alert-warning d-none mt-3" role="alert">
                                    <i class="ri-alert-line"></i> <strong>Peringatan:</strong> Foto tidak memiliki rasio 3:4. Foto akan dipotong otomatis untuk sesuai rasio yang benar.
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Upload Berkas/File -->
                    <div class="mb-4">
                        <label for="berkas" class="form-label">Upload Dokumen/File (Opsional)</label>
                        <input class="form-control @error('berkas') is-invalid @enderror" 
                               type="file" 
                               name="berkas" 
                               id="berkas" 
                               accept="image/png, image/jpeg, application/pdf, .doc, .docx">
                        
                        <!-- Image Preview -->
                        <div class="mt-2">
                            <img id="berkas-preview" 
                                 src="#" 
                                 alt="Pratinjau Gambar" 
                                 class="img-thumbnail" 
                                 style="display: none; max-height: 200px;"/>
                        </div>

                        <!-- Current File Display -->
                        @if($ppds && $ppds->path_berkas)
                            <div class="mt-3">
                                <div class="alert alert-info p-2 mb-0">
                                    <small>
                                        <strong>Dokumen saat ini:</strong><br>
                                        <a href="{{ Storage::url($ppds->path_berkas) }}" 
                                           target="_blank" 
                                           class="fw-medium">
                                            <i class="ri-eye-line"></i> Lihat Dokumen
                                        </a>
                                        | 
                                        <a href="{{ route('ppds.download', $ppds->id) }}" 
                                           class="fw-medium">
                                            <i class="ri-download-line"></i> Download
                                        </a>
                                    </small>
                                </div>
                            </div>
                        @endif

                        <div class="form-text">Tipe file yang diizinkan: PDF, DOC, DOCX, JPG, PNG. Maksimal 5MB.</div>
                        @error('berkas')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary w-md">Batal</a>
                            <button type="submit" class="btn btn-primary w-md">
                                <i class="ri-save-line"></i> Simpan Profil & Data PPDS
                            </button>
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
        // ===== Handle Foto Profil =====
        const fotoPROFILInput = document.getElementById('foto_profil');
        const fotoPROFILPreview = document.getElementById('foto-profil-preview');
        const fotoInfoDiv = document.getElementById('foto-info');
        const fileSizeSpan = document.getElementById('file-size');
        const fileDimensionsSpan = document.getElementById('file-dimensions');
        const aspectRatioSpan = document.getElementById('aspect-ratio');
        const aspectRatioWarning = document.getElementById('aspect-ratio-warning');

        fotoPROFILInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                // Show file info
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileSizeSpan.textContent = fileSizeMB + ' MB';
                fotoInfoDiv.style.display = 'block';

                // Read image dimensions
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        const width = img.width;
                        const height = img.height;
                        const ratio = (width / height).toFixed(2);
                        const targetRatio = (3 / 4).toFixed(2);
                        
                        fileDimensionsSpan.textContent = width + ' x ' + height + ' px';
                        aspectRatioSpan.textContent = ratio;
                        
                        // Check if ratio is 3:4 (tolerance ±5%)
                        const ratioDifference = Math.abs(ratio - targetRatio);
                        if (ratioDifference > 0.05) {
                            aspectRatioSpan.classList.remove('bg-success');
                            aspectRatioSpan.classList.add('bg-warning');
                            aspectRatioWarning.classList.remove('d-none');
                        } else {
                            aspectRatioSpan.classList.remove('bg-warning');
                            aspectRatioSpan.classList.add('bg-success');
                            aspectRatioWarning.classList.add('d-none');
                        }
                        
                        // Show preview
                        if (!fotoPROFILPreview) {
                            // Create preview image if doesn't exist
                            const previewImg = document.createElement('img');
                            previewImg.id = 'foto-profil-preview';
                            previewImg.style.width = '100%';
                            previewImg.style.height = '100%';
                            previewImg.style.objectFit = 'cover';
                            const previewContainer = document.querySelector('[style*="aspect-ratio: 3/4"]');
                            previewContainer.innerHTML = '';
                            previewContainer.appendChild(previewImg);
                            previewImg.src = e.target.result;
                        } else {
                            fotoPROFILPreview.src = e.target.result;
                        }
                    };
                    img.src = e.target.result;
                };
                
                reader.readAsDataURL(file);
            } else {
                fotoInfoDiv.style.display = 'none';
                aspectRatioWarning.classList.add('d-none');
            }
        });

        // ===== Handle Dokumen/Berkas =====
        const berkasInput = document.getElementById('berkas');
        const berkasPreview = document.getElementById('berkas-preview');

        if (berkasInput) {
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
        }
    });
</script>
@endpush
