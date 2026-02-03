@extends('layouts.master')
@section('title')
    Starter
@endsection
@section('content')
    <x-breadcrumb pagetitle="Utility" title="Starter page" />
@endsection
@push('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush
