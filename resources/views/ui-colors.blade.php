@extends('layouts.master')
@section('title')
    Colors
@endsection
@section('content')

<x-breadcrub pagetitle="Morvin" subtitle="UI Elements" title="Colors"  />
    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-primary p-4 rounded">
                                <h5 class="my-2 text-white">#525ce5</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-primary">Primary</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-success p-4 rounded">
                                <h5 class="my-2 text-white">#34c38f</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-success">Success</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-info p-4 rounded">
                                <h5 class="my-2 text-white">#50a5f1</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-info">Info</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-warning p-4 rounded">
                                <h5 class="my-2 text-white">#f1b44c</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-warning">Warning</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-danger p-4 rounded">
                                <h5 class="my-2 text-white">#f46a6a</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-danger">Danger</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-dark p-4 rounded">
                                <h5 class="my-2 text-light">#343a40</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-dark">Dark</h5>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="color-box bg-secondary p-4 rounded">
                                <h5 class="my-2 text-light">#74788d</h5>
                            </div>
                            <h5 class="mb-0 mt-3 text-muted">Secondary</h5>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
