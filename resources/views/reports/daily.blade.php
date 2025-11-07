@extends('layouts.master')
@section('title', 'Laporan Harian')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Reports" title="Laporan Harian" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <!-- FILTER FORM -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3 fw-semibold">Filter Laporan</h5>
                <form action="{{ route('reports.daily') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Device</label>
                        <select name="device_id" class="form-control">
                            <option value="">-- Semua Device --</option>
                            @foreach($devices as $device)
                                <option value="{{ $device->id }}" {{ request('device_id') == $device->id ? 'selected' : '' }}>
                                    {{ $device->serial_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Train</label>
                        <select name="train_id" class="form-control">
                            <option value="">-- Semua Train --</option>
                            @foreach($trains as $train)
                                <option value="{{ $train->id }}" {{ request('train_id') == $train->id ? 'selected' : '' }}>
                                    {{ $train->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-filter"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Laporan Harian</h5>
                <div>
                    <a href="{{ route('reports.daily.export', request()->query()) }}" class="btn btn-success btn-sm">
                        <i class="mdi mdi-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('reports.daily.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                        <i class="mdi mdi-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Device</th>
                            <th>Temperature (°C)</th>
                            <th>Humidity (%)</th>
                            <th>Noise (dB)</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $log->device->serial_number ?? '-' }}</td>
                                <td>{{ number_format($log->temperature, 1) }}</td>
                                <td>{{ number_format($log->humidity, 1) }}</td>
                                <td>{{ number_format($log->noise, 1) }}</td>
                                <td>{{ $log->timestamp }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
@endsection
