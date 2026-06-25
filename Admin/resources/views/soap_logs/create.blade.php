@extends('layouts.master')

@section('title','New SOAP Log')

@section('content')
<div class="row">
    <div class="col-xl-10 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white pt-4 pb-3">
                <h4 class="card-title mb-0 text-white"><i class="ri-add-circle-line align-middle me-2"></i>FORM SOAP</h4>
                <p class="card-title-desc text-white-50 mb-0 mt-1">Lengkapi form rekam medis di bawah ini dengan seksama.</p>
            </div>
            <div class="card-body p-4">
                <form id="soap_log_form" action="{{ route('ppds.soap-logs.store') }}" method="POST" novalidate>
                    @include('soap_logs.form')
                    <hr class="my-4">
                    <div class="d-flex justify-content-end align-items-center">
                        <a href="{{ route('ppds.soap-logs.index') }}" class="btn btn-light btn-lg waves-effect me-3">
                            <i class="ri-arrow-go-back-line align-middle me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-light">
                            <i class="ri-save-line align-middle me-1"></i> Simpan SOAP Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    {{-- Tom-select untuk dropdown yang bisa dicari --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tom Select initialization dapat ditambahkan di sini jika ada field lain yang membutuhkan
        });
    </script>
@endsection
