@extends('layouts.master')
@section('title')
    Vector Maps
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <x-breadcrub pagetitle="Morvin" subtitle="Maps" title="Vector Maps"  />


    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">World Vector Map</h4>
                            <p class="card-title-dsec">Example of world vector maps.</p>
                            <div id="world-map-markers" style="height: 420px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">USA Vector Map</h4>
                            <p class="card-title-dsec">Example of united states of ameria vector maps.</p>
                            <div id="usa-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">India Vector Map</h4>
                            <p class="card-title-dsec">Example of india vector maps.</p>
                            <div id="india-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Australia Vector Map</h4>
                            <p class="card-title-dsec">Example of australia vector maps.</p>
                            <div id="australia-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Chicago Vector Map</h4>
                            <p class="card-title-dsec">Example chicago of vector maps.</p>
                            <div id="chicago-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">UK Vector Map</h4>
                            <p class="card-title-dsec">Example of united kingdom vector maps.</p>
                            <div id="uk-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Canada Vector Map</h4>
                            <p class="card-title-dsec">Example canada of vector maps.</p>
                            <div id="canada-vectormap" style="height: 350px"></div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- Plugins js-->
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-in-mill-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-au-mill-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-il-chicago-mill-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-uk-mill-en.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-ca-lcc-en.js') }}"></script>

    <!-- Init js-->
    <script src="{{ URL::asset('build/js/pages/vector-maps.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
