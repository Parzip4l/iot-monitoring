@extends('layouts.master')
@section('title')
    Chartjs Charts
@endsection
@section('content')
    <x-breadcrub pagetitle="Morvin" subtitle="Charts" title="Chartjs Charts" />

    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Bar Chart</h4>
                            <p class="card-title-desc">Example of bar chart chart js.</p>

                            <canvas id="barChart"></canvas>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Line Chart</h4>
                            <p class="card-title-desc">Example of line chart chart js.</p>

                            <canvas id="lineChart"></canvas>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Pie chart</h4>
                            <p class="card-title-desc">Example of line pie chart js.</p>
                            <div style="height: 300px; ">
                                <canvas id="pie" class="mx-auto"> </canvas>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Donut chart</h4>
                            <p class="card-title-desc">Example of donut chart js.</p>
                            <div style="height: 300px; ">
                                <canvas id="doughnut" class="mx-auto"> </canvas>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row-->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Radar chart</h4>
                            <p class="card-title-desc">Example of radar chart js.</p>
                            <div style="height: 300px; ">
                                <canvas id="radar" class="mx-auto"> </canvas>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row-->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- Chart JS -->
    <script type="module" src="{{ URL::asset('build/libs/chart.js/chart.umd.js') }}"></script>
    <script type="module" src="{{ URL::asset('build/js/pages/chartjs.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
