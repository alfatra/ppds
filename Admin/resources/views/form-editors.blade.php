@extends('layouts.master')
@section('title')
    Form Editor
@endsection
@section('content')
    <x-breadcrumb pagetitle="Forms" title="Form Editor" />


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Tinymce wysihtml5</h4>
                    <p class="card-title-desc">Bootstrap-wysihtml5 is a javascript
                        plugin that makes it easy to create simple, beautiful wysiwyg editors
                        with the help of wysihtml5 and Twitter Bootstrap.</p>

                    <form method="post">
                        <textarea id="elm1" name="area"></textarea>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@push('script')
    <!--tinymce js-->
    <script src="{{ URL::asset('build/libs/tinymce/tinymce.min.js') }}"></script>

    <!-- init js -->
    <script src="{{ URL::asset('build/js/pages/form-editor.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endpush
