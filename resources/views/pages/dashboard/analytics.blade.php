@extends('layouts.master')
@section('title', 'Analytics IoT')
@section('css')
    <link href="{{ URL::asset('build/libs/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Analytics" title="IoT Analytics" />

<div class="container-fluid">
    <div class="page-content-wrapper">

        {{-- ===== Filters ===== --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <label for="filter-train">Pilih Kereta</label>
                        <select class="form-select w-100" id="filter-train">
                            <option value="">Semua</option>
                            @foreach($trains as $train)
                                <option value="{{ $train->id }}">{{ $train->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <label for="filter-range">Dari Tanggal</label>
                        <input type="date" id="start-date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <label for="filter-range">Sampai Tanggal</label>
                       <input type="date" id="end-date" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== KPI Cards ===== --}}
        <div class="row mb-4" id="kpi-cards">
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="mdi mdi-thermometer text-danger font-size-24 mb-2"></i>
                        <p class="text-muted mb-1">Rata-rata Suhu</p>
                        <h5 class="fw-bold" id="avg-temperature">--</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="mdi mdi-water-percent text-info font-size-24 mb-2"></i>
                        <p class="text-muted mb-1">Rata-rata Humidity</p>
                        <h5 class="fw-bold" id="avg-humidity">--</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="mdi mdi-volume-high text-warning font-size-24 mb-2"></i>
                        <p class="text-muted mb-1">Rata-rata Noise</p>
                        <h5 class="fw-bold" id="avg-noise">--</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="mdi mdi-alert-circle text-danger font-size-24 mb-2"></i>
                        <p class="text-muted mb-1">Jumlah Anomali</p>
                        <h5 class="fw-bold text-danger" id="anomali-count">--</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Chart ===== --}}
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Trend Sensor</h4>
                        <div id="iot-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Persentase Anomali per Sensor</h4>
                        <div id="anomali-pie" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Table ===== --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Log Sensor</h4>
                        <div class="table-responsive">
                            <table id="iot-log-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Device</th>
                                        <th>Temperature</th>
                                        <th>Humidity</th>
                                        <th>Noise</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
let iotChart, logTable, anomalyPieChart;
document.addEventListener("DOMContentLoaded", function() {
    // Init Table
    logTable = $('#iot-log-table').DataTable({
        responsive: true,
        columns: [
            {data:'id'}, {data:'device'}, {data:'temperature'}, {data:'humidity'}, {data:'noise'}, {data:'timestamp'}
        ]
    });

    // Init Chart
    const chartOptions = {
        chart: { 
            type: 'line', 
            height: 400, 
            animations:{enabled:true},
            zoom: { type:'x', enabled:true, autoScaleYaxis:true },
            toolbar: { show:true, tools:{ selection:true, zoom:true, zoomin:true, zoomout:true, reset:true } }
        },
        series: [],
        xaxis: { type: 'datetime' },
        stroke: { curve:'smooth' },
        tooltip: { x:{ format:'dd MMM yyyy HH:mm:ss' } },
        colors: ['#FF4560','#008FFB','#00E396'], // merah, biru, hijau
        yaxis: [
            { title: { text: 'Temperature (°C)' } },
            { opposite: true, title: { text: 'Humidity (%)' } },
            { opposite: true, title: { text: 'Noise (dB)' } }
        ],
        legend: { position:'top', horizontalAlign:'center' }
    };
    
    iotChart = new ApexCharts(document.querySelector("#iot-chart"), chartOptions);
    iotChart.render();

    const pieOptions = {
        chart: { type: 'pie', height: 350 },
        series: [0,0,0], // default
        labels: ['Temperature', 'Humidity', 'Noise'],
        legend: { position: 'bottom' },
        colors: ['#FF4560','#008FFB','#00E396']
    };
    anomalyPieChart = new ApexCharts(document.querySelector("#anomali-pie"), pieOptions);
    anomalyPieChart.render();

    // Load Data
    loadAnalytics();

    // Filter events
    document.getElementById('filter-train').addEventListener('change', loadAnalytics);
    document.getElementById('start-date').addEventListener('change', loadAnalytics);
    document.getElementById('end-date').addEventListener('change', loadAnalytics);
});

async function loadAnalytics(){
    const trainId = document.getElementById('filter-train').value;
    const startDate = document.getElementById('start-date').value;
    const endDate   = document.getElementById('end-date').value;

    try {
        const res = await axios.get("{{ route('analytics.iot.data') }}", { 
            params:{train_id:trainId, start:startDate, end:endDate}
        });
        const data = res.data;

        // ===== KPI =====
        document.getElementById('avg-temperature').innerText = data.avg_temperature ? data.avg_temperature+' °C' : '--';
        document.getElementById('avg-humidity').innerText    = data.avg_humidity ? data.avg_humidity+' %' : '--';
        document.getElementById('avg-noise').innerText       = data.avg_noise ? data.avg_noise+' dB' : '--';
        document.getElementById('anomali-count').innerText   = data.anomaly_count ?? '--';

        // ===== Line Chart =====
        const temperatureSeries = [], humiditySeries = [], noiseSeries = [];
        if(data.logs && data.logs.length){
            data.logs.forEach(l => {
                const ts = new Date(l.timestamp).getTime();
                temperatureSeries.push([ts, parseFloat(l.temperature)]);
                humiditySeries.push([ts, parseFloat(l.humidity)]);
                noiseSeries.push([ts, parseFloat(l.noise)]);
            });
        }
        iotChart.updateSeries([
            { name: 'Temperature (°C)', data: temperatureSeries },
            { name: 'Humidity (%)', data: humiditySeries },
            { name: 'Noise (dB)', data: noiseSeries }
        ]);

        // ===== Table =====
        logTable.clear();
        if(data.logs && data.logs.length){
            logTable.rows.add(data.logs.map((l,i)=>({
                id:i+1,
                device:l.device?.serial_number ?? 'Unknown',
                temperature:l.temperature,
                humidity:l.humidity,
                noise:l.noise,
                timestamp:l.timestamp
            })));
        }
        logTable.draw();

        // ===== Pie Chart =====
        if(data.anomaly_pie){
            anomalyPieChart.updateSeries([
                data.anomaly_pie.temperature ?? 0,
                data.anomaly_pie.humidity ?? 0,
                data.anomaly_pie.noise ?? 0
            ]);
        }

    } catch(err){
        console.error(err);
    }
}



</script>
@endsection
