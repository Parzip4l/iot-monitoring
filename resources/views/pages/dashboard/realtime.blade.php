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
                    <div class="col-xl-12">
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
                    
                </div>

                {{-- ===== Diagram Kereta ===== --}}
                <!-- Diagram Kereta & Gerbong (SVG) -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Diagram Kereta & Gerbong</h4>

                        <div class="swiper-container train-slider">
                            <div class="swiper-wrapper">
                                @foreach ($trains as $train)
                                    <div class="swiper-slide" data-train-id="{{ $train->id }}">
                                        <div class="train-diagram text-center position-relative">
                                            <h5 class="mb-3 fw-bold">{{ $train->name }}</h5>
                                            <img src="{{ URL::asset('kereta.png') }}" alt="Kereta LRT" class="img-fluid" style="max-width:60%;">
                                            
                                            <div class="gerbong-indicators-container"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>

                        <p class="text-muted text-center small mt-3 mb-0">
                            Geser untuk memilih kereta. | 🟢 Online | 🔴 Offline
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
                                <h5 id="temperature-card-value" class="fw-bold">28°C</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm h-100">
                            <div class="card-body">
                                <i class="mdi mdi-water-percent text-info font-size-24 mb-2"></i>
                                <p class="text-muted mb-1">Rata-rata Humidity</p>
                                <h5 id="humidity-card-value" class="fw-bold">65%</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm h-100">
                            <div class="card-body">
                                <i class="mdi mdi-volume-high text-warning font-size-24 mb-2"></i>
                                <p class="text-muted mb-1">Rata-rata Noise</p>
                                <h5 id="noise-card-value" class="fw-bold">78 dB</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center shadow-sm h-100">
                            <div class="card-body">
                                <i class="mdi mdi-alert-circle text-danger font-size-24 mb-2"></i>
                                <p class="text-muted mb-1">Jumlah Anomali Hari Ini</p>
                                <h5 id="anomaly-card-value" class="fw-bold text-danger">3</h5>
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
                                        <tbody id="alert-table-body">
                                            @forelse ($alertTerbaru as $alert)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($alert->created_at)->format('H:i:s') }}</td>
                                                    <td>{{ $alert->device->train->name ?? '-' }}</td>
                                                    <td>{{ $alert->device->cars->car_number ?? '-' }}</td>
                                                    <td>{{ $alert->device->serial_number ?? '-' }}</td>
                                                    <td>{{ $alert->message ?? 'Tidak ada pesan' }}</td>
                                                    <td>
                                                        @php
                                                            $status = strtolower($alert->status ?? 'anomali');
                                                            $badgeClass = match($status) {
                                                                'critical' => 'bg-danger',
                                                                'offline' => 'bg-secondary',
                                                                'warning', 'anomali' => 'bg-warning text-dark',
                                                                default => 'bg-info',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Belum ada notifikasi terbaru</td>
                                                </tr>
                                            @endforelse
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
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
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
        <script>
            async function loadAlerts() {
                try {
                    const response = await fetch('/api/alerts/latest');
                    const data = await response.json();

                    const tbody = document.querySelector('#alert-table-body');
                    tbody.innerHTML = '';

                    data.forEach(alert => {
                        const row = `
                            <tr>
                                <td>${alert.time}</td>
                                <td>${alert.train}</td>
                                <td>${alert.car}</td>
                                <td>${alert.device}</td>
                                <td>${alert.message}</td>
                                <td><span class="badge ${alert.badge}">${alert.status}</span></td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', row);
                    });
                } catch (error) {
                    console.error('Gagal memuat notifikasi:', error);
                }
            }

            // refresh setiap 10 detik
            setInterval(loadAlerts, 10000);
            loadAlerts();
        </script>

    @endsection
