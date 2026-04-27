@extends('layouts.master')

@section('title','Edit SOAP Log')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-0">Edit SOAP Entry #{{ $log->id }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('ppds.soap-logs.update',$log) }}" method="POST">
                @method('PUT')
                @include('soap_logs.form')
                <button class="btn btn-primary">Update</button>
            </form>
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
