@extends('layouts.master')
@section('title')
	{{ isset($ppds) ? 'Edit Data PPDS' : 'Tambah Data PPDS' }}
@endsection
@section('content')
	<x-breadcrumb pagetitle="PPDS" title="{{ isset($ppds) ? 'Edit Data' : 'Tambah Data' }}" />

	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">{{ isset($ppds) ? 'Formulir Edit Data PPDS' : 'Formulir Input Data PPDS' }}</h4>
					<p class="card-title-desc">Isi semua data yang diperlukan pada form di bawah ini.</p>
				</div>
				<div class="card-body p-4">

					{{-- Mengarahkan form ke route 'ppds.store' --}}
					<form action="{{ isset($ppds) ? route('ppds.update', $ppds->id) : route('ppds.store') }}" method="POST">
						@csrf
						@if(isset($ppds))
							@method('PUT')
						@endif
						<div class="row">
							<div class="col-lg-6">
								<div>
									<div class="mb-3">
										<label for="nama_lengkap" class="form-label">Nama Lengkap</label>
										<input class="form-control" type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" id="nama_lengkap"
											value="{{ old('nama_lengkap', $ppds->nama_lengkap ?? '') }}" required>
									</div>
									<div class="mb-3">
										<label for="email" class="form-label">Email</label>
										<input class="form-control" type="email" name="email" placeholder="contoh@email.com" id="email"
											value="{{ old('email', $ppds->email ?? '') }}" required>
									</div>
									<div class="mb-3">
										<label for="telepon" class="form-label">Telepon</label>
										<input class="form-control" type="tel" name="telepon" placeholder="081234567890" id="telepon"
											value="{{ old('telepon', $ppds->telepon ?? '') }}">
									</div>
								</div>
							</div>

							<div class="col-lg-6">
								<div class="mt-3 mt-lg-0">
									<div class="mb-3">
										<label for="agama" class="form-label">Agama</label>
										<select class="form-select" name="agama" id="agama">
											<option>Pilih Agama</option>
											@php
												$selectedAgama = old('agama', $ppds->agama ?? '');
											@endphp
											<option value="Islam" {{ $selectedAgama == 'Islam' ? 'selected' : '' }}>Islam</option>
											<option value="Kristen Protestan" {{ $selectedAgama == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
											<option value="Katolik" {{ $selectedAgama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
											<option value="Hindu" {{ $selectedAgama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
											<option value="Buddha" {{ $selectedAgama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
											<option value="Khonghucu" {{ $selectedAgama == 'Khonghucu' ? 'selected' : '' }}>Khonghucu</option>
										</select>
									</div>
									<div class="mb-3">
										<label for="alamat" class="form-label">Alamat</label>
										<textarea id="alamat" class="form-control" name="alamat" rows="5" placeholder="Masukkan alamat lengkap">{{ old('alamat', $ppds->alamat ?? '') }}</textarea>
									</div>
								</div>
							</div>
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