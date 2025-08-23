@extends('layouts.master')
@section('title')
    Chartist Charts
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/chartist/chartist.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    
<x-breadcrub pagetitle="Morvin" subtitle="Charts" title="Chartist Charts"  />

    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Overlapping bars on mobile</h4>
                            <p class="card-title-desc">This example makes use of label interpolation and the
                                seriesBarDistance property that allows you to make bars overlap over each other.</p>

                            <div id="overlapping-bars" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Stacked bar chart</h4>
                            <p class="card-title-desc">You can also set your bar chart to stack the series bars on top of
                                each other easily by using the stackBars property in your configuration.</p>

                            <div id="stacked-bar-chart" class="ct-chart ct-golden-section" dir="ltr"></div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Animating a Donut with Svg.animate</h4>
                            <p class="card-title-desc">Although it'd be also possible to achieve this animation with CSS,
                                with some minor suboptimal things, here's an example of how to animate donut charts using
                                Chartist.Svg.animate and SMIL.</p>

                            <div id="animating-donut" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Simple pie chart</h4>
                            <p class="card-title-desc">A very simple pie chart with label interpolation to show percentage
                                instead of the actual data series value.
                            </p>

                            <div id="simple-pie" class="ct-chart ct-golden-section simple-pie-chart-chartist"
                                dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Advanced Smil Animations</h4>
                            <p class="card-title-desc">Chartist provides a simple API to animate the elements on the Chart
                                using SMIL.</p>

                            <div id="smil-animations" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Simple line chart</h4>
                            <p class="card-title-desc">An example of a simple line chart with three series. You can edit
                                this example in realtime.</p>

                            <div id="simple-line-chart" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Line Scatter Diagram</h4>
                            <p class="card-title-desc">This advanced example uses a line chart to draw a scatter diagram.
                                The data object is created with a functional style random mechanism.</p>

                            <div id="scatter-diagram" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Line chart with area</h4>
                            <p class="card-title-desc">This chart uses the showArea option to draw line, dots but also an
                                area shape. Use the low option to specify a fixed lower bound that will make the area
                                expand.</p>

                            <div id="chart-with-area" class="ct-chart ct-golden-section" dir="ltr"></div>

                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- Plugin Js-->
    <script src="{{ URL::asset('build/libs/chartist/chartist.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/chartist-plugin-tooltips-updated/chartist-plugin-tooltip.min.js') }}"></script>
    <!-- demo js-->
    <script src="{{ URL::asset('build/js/pages/chartist.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
