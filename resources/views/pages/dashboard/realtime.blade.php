@extends('layouts.master')
@section('title')
    Dashboard Monitoring
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <x-breadcrub pagetitle="LRTJ" subtitle="Dashboard" title="Monitoring"  />

    <div class="container-fluid">
        <div class="page-content-wrapper">

            {{-- ===== Informasi Global & Filter ===== --}}
            <div class="row mb-3">
                <div class="col-xl-9">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card mini-stat text-center shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Kereta Aktif</p>
                                    <h4 class="fw-bold">{{$keretaAktif}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mini-stat text-center shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Device Online</p>
                                    <h4 class="fw-bold text-success">{{$deviceOnline}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mini-stat text-center shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Device Offline</p>
                                    <h4 class="fw-bold text-danger">{{$deviceOffline}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card mini-stat text-center shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted mb-2">Status Sistem</p>
                                    <span class="badge bg-success rounded-pill px-3 py-2">{{$statusSistem}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Filter Kereta -->
                <div class="col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <label for="">Pilih Kereta</label>
                            <select class="form-select w-100">
                                <option>Semua</option>
                                <option>Kereta 1</option>
                                <option>Kereta 2</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Diagram Kereta ===== --}}
            <!-- Diagram Kereta & Gerbong (SVG) -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="header-title mb-3">Diagram Kereta & Gerbong</h4>

                    <div class="train-diagram text-center position-relative">
                        <!-- Gambar kereta -->
                        <img src="{{ URL::asset('kereta.png') }}" alt="Kereta LRT" class="img-fluid" style="max-width:60%;">

                        <!-- Indikator Gerbong 1 -->
                        <div class="iot-status position-absolute" style="top:5%; left:20%;">
                            <span class="badge bg-success">Gerbong 1: 4 Devices</span>
                            <div class="line-indicator"></div>
                        </div>

                        <!-- Indikator Gerbong 2 -->
                        <div class="iot-status position-absolute" style="top:5%; left:70%;">
                            <span class="badge bg-danger">Gerbong 2: 1 Offline</span>
                            <div class="line-indicator"></div>
                        </div>
                    </div>

                    <p class="text-muted text-center small mt-3 mb-0">
                        🟢 Normal | 🟡 Anomali | 🔴 Offline
                    </p>
                </div>
            </div>



            {{-- ===== Realtime Summary (line chart + widgets) ===== --}}
            <div class="row mb-2">
                <div class="col-xl-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="header-title mb-4 float-sm-start">Realtime Summary</h4>
                            <div class="float-sm-end">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#">Realtime</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">5 Min</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">1 Hour</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">1 Day</a></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>

                            <div class="row align-items-center">
                                <div class="col-xl-9">
                                    <div>
                                        <div id="realtime-sensor-chart" class="apex-charts" dir="ltr"></div>
                                    </div>
                                </div>

                                <div class="col-xl-3">
                                    <div class="dash-info-widget mt-4 mt-lg-0 py-4 px-3 rounded">

                                        <!-- Temperature -->
                                        <div class="media dash-main-border pb-2 mt-2">
                                            <div class="avatar-sm mb-3 mt-2">
                                                <span class="avatar-title rounded-circle bg-white shadow">
                                                    <i class="mdi mdi-thermometer text-danger font-size-18"></i>
                                                </span>
                                            </div>
                                            <div class="media-body ps-3">
                                                <h4 id="temperature-value" class="font-size-20">28°C</h4>
                                                <p class="text-muted">Temperature</p>
                                            </div>
                                        </div>

                                        <!-- Humidity -->
                                        <div class="media mt-4 dash-main-border pb-2">
                                            <div class="avatar-sm mb-3 mt-2">
                                                <span class="avatar-title rounded-circle bg-white shadow">
                                                    <i class="mdi mdi-water-percent text-info font-size-18"></i>
                                                </span>
                                            </div>
                                            <div class="media-body ps-3">
                                                <h4 id="humidity-value" class="font-size-20">65%</h4>
                                                <p class="text-muted">Humidity</p>
                                            </div>
                                        </div>

                                        <!-- Noise -->
                                        <div class="media mt-4">
                                            <div class="avatar-sm mb-2 mt-2">
                                                <span class="avatar-title rounded-circle bg-white shadow">
                                                    <i class="mdi mdi-volume-high text-warning font-size-18"></i>
                                                </span>
                                            </div>
                                            <div class="media-body ps-3">
                                                <h4 id="noise-value" class="font-size-20">78 dB</h4>
                                                <p class="text-muted mb-0">Noise Level</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                    </div>
                </div>
            </div>


            {{-- ===== Summary Sensor (cards) ===== --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-thermometer text-danger font-size-24 mb-2"></i>
                            <p class="text-muted mb-1">Rata-rata Suhu</p>
                            <h5 class="fw-bold">28°C</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-water-percent text-info font-size-24 mb-2"></i>
                            <p class="text-muted mb-1">Rata-rata Humidity</p>
                            <h5 class="fw-bold">65%</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-volume-high text-warning font-size-24 mb-2"></i>
                            <p class="text-muted mb-1">Rata-rata Noise</p>
                            <h5 class="fw-bold">78 dB</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-alert-circle text-danger font-size-24 mb-2"></i>
                            <p class="text-muted mb-1">Jumlah Anomali Hari Ini</p>
                            <h5 class="fw-bold text-danger">3</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Notifikasi Terbaru ===== --}}
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Notifikasi Terbaru</h4>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Kereta</th>
                                            <th>Gerbong</th>
                                            <th>Device</th>
                                            <th>Pesan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>10:22</td>
                                            <td>Kereta 2</td>
                                            <td>Gerbong 1</td>
                                            <td>DEV-005</td>
                                            <td>Noise > 90dB</td>
                                            <td><span class="badge bg-warning">Anomali</span></td>
                                        </tr>
                                        <tr>
                                            <td>09:58</td>
                                            <td>Kereta 1</td>
                                            <td>Gerbong 2</td>
                                            <td>DEV-003</td>
                                            <td>Humidity < 30%</td>
                                            <td><span class="badge bg-danger">Critical</span></td>
                                        </tr>
                                        <tr>
                                            <td>09:30</td>
                                            <td>Kereta 2</td>
                                            <td>Gerbong 2</td>
                                            <td>DEV-007</td>
                                            <td>Disconnected</td>
                                            <td><span class="badge bg-secondary">Offline</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- <p class="text-muted small mt-2 mb-0">* Data di atas masih dummy, nanti otomatis diisi via API</p> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Plugins js-->
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- Custom dashboard js -->
    <script src="{{ URL::asset('build/js/pages/dashboard-monitoring.init.js') }}"></script>
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <style>
        .iot-status {
            display: inline-block;
            text-align: center;
        }
        .iot-status .line-indicator {
            width: 2px;
            height: 80px; /* panjang garis bisa diatur */
            background: #333;
            margin: 5px auto 0 auto;
        }
    </style>

    <script>
        // Dummy dynamic update IoT indikator
        const statuses = ["success", "warning", "danger"];
        setInterval(() => {
            document.querySelectorAll(".iot-status span").forEach(el => {
                let rand = statuses[Math.floor(Math.random() * statuses.length)];
                el.className = "badge bg-" + rand;
                el.textContent = 
                    rand === "success" ? "Gerbong: 4 Devices" : 
                    rand === "warning" ? "Gerbong: 1 Anomali" : 
                    "Gerbong: 1 Offline";
            });
        }, 4000);
    </script>
@endsection
