@extends('layouts.master')
@section('title')
    Remix Icons
@endsection
@section('content')
    <x-breadcrumb pagetitle="Icons" title="Remix Icons" />

    <div class="row">

        <div class="col-12" id="icons"></div> <!-- end col-->

    </div><!-- end row -->
@endsection
@push('script')
    <!-- Remix icon js-->
    <script src="{{ URL::asset('build/js/pages/remix-icons-list.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush
