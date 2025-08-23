@extends('layouts.master')
@section('title')
    Form Upload
@endsection
@section('css')
    <!-- Plugins css -->
    <link href="{{ URL::asset('build/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    
<x-breadcrub pagetitle="Morvin" subtitle="Forms" title="Form Upload"  />

    <div class="container-fluid">
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Dropzone</h4>
                            <p class="card-title-desc">DropzoneJS is an open source library
                                that provides drag’n’drop file uploads with image previews.
                            </p>

                            <div>
                                <form action="#" class="dropzone">
                                    <div class="fallback">
                                        <input name="file" type="file" multiple="multiple">
                                    </div>
                                    <div class="dz-message needsclick">
                                        <div class="mb-3">
                                            <i class="display-4 text-muted mdi mdi-cloud-upload-outline"></i>
                                        </div>

                                        <h4>Drop files here to upload</h4>
                                    </div>
                                </form>
                            </div>

                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-primary waves-effect waves-light">Send Files</button>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- Plugins js -->
    <script src="{{ URL::asset('build/libs/dropzone/min/dropzone.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
