    <!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title> @yield('title') | Monitoring Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="LRTJ - Admin & Dashboard Monitoring" name="description" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('favicon.png') }}">

        <!-- include head css -->
        @include('layouts.head-css')
    </head>

    <body data-layout-size="boxed" data-layout="horizontal">
        <!-- Begin page -->
        <div id="layout-wrapper">
            <!-- topbar -->

            <!-- sidebar components -->
            @include('layouts.horizontal')

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    @yield('content')

                </div>
                <!-- end page content-->

                <!-- footer -->
                @include('layouts.footer')
            </div>
            <!-- end main content-->
        </div>
        <!-- END layout-wrapper -->

        <!-- customizer -->
        @include('layouts.right-sidebar')

        <!-- vendor-scripts -->
        @include('layouts.vendor-scripts')

    </body>

    </html>
