@extends('layouts.master')

@section('title')
    Edit Tindakan Medis
@endsection

@section('content')
    <x-breadcrumb pagetitle="Admin" title="Edit Tindakan Medis" />

    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Tindakan Medis</h4>
                    <p class="card-title-desc">Silakan ubah data master tindakan medis di bawah ini.</p>

                    <form action="{{ route('admin.medical-activities.update', $medicalActivity->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Tindakan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $medicalActivity->name) }}" required placeholder="Masukkan nama tindakan medis">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Tambahkan deskripsi jika perlu">{{ old('description', $medicalActivity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.medical-activities.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
