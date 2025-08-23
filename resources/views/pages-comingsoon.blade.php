@extends('layouts.master-without-nav')
@section('title')
    Coming Soon
@endsection
@section('content')
    <div class="home-btn d-none d-sm-block">
        <a href="index"><i class="fas fa-home h2"></i></a>
    </div>

    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5">
                        <a href="index" class="logo"><img src="{{ URL::asset('build/images/logo-dark.png') }}" height="24"
                                alt="logo"></a>
                        <h5 class="font-size-16 mb-4">Responsive Bootstrap 5 Admin Dashboard</h5>

                        <h4 class="mt-5">Let's get started with Morvin</h4>
                        <p class="text-muted">It will be as simple as Occidental in fact it will be Occidental.</p>

                        <div class="mt-4">
                            <img src="{{ URL::asset('build/images/coming-soon.png') }}" class="img-fluid" alt="">
                        </div>

                        <div class="row justify-content-center mt-5 pt-3">
                            <div class="col-md-8">
                                <div data-countdown="2022/12/31" class="counter-number"></div>
                            </div> <!-- end col-->
                        </div> <!-- end row-->
                    </div>
                </div><!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end Account pages -->
@endsection
@section('scripts')
    <!-- Plugins js-->
    <script src="{{ URL::asset('build/libs/jquery-countdown/jquery.countdown.min.js') }}"></script>

    <!-- Countdown js -->
    <script src="{{ URL::asset('build/js/pages/coming-soon.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
