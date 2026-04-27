@extends('layouts.master')

@section('title') Absensi @endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Menu @endslot
        @slot('title') Absensi @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Halaman Absensi</h4>
                    <p class="card-title-desc">Fitur untuk menampilkan dan mengelola data absensi akan dikembangkan di sini.</p>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection