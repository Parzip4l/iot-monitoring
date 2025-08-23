@extends('layouts.master')
@section('title')
    Blank Page
@endsection
@section('content')

    <x-breadcrub pagetitle="Morvin" subtitle="Pages" title="Blank Page"  />

    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-4" style="height: 300px;">

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container-fluid -->

@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
