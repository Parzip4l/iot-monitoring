@extends('layouts.master')
@section('title', 'Sensor Anomalies')
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Sensor Anomalies" title="Anomalies Log"  />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <form action="{{ route('anomaly.index') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Device</label>
                            <select name="device_id" class="form-control">
                                <option value="">-- All Devices --</option>
                                @foreach($devices as $device)
                                    <option value="{{ $device->id }}" {{ request('device_id')==$device->id ? 'selected' : '' }}>
                                        {{ $device->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Train</label>
                            <select name="train_id" class="form-control">
                                <option value="">-- All Trains --</option>
                                @foreach($trains as $train)
                                    <option value="{{ $train->id }}" {{ request('train_id')==$train->id ? 'selected' : '' }}>
                                        {{ $train->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Car</label>
                            <select name="car_id" class="form-control">
                                <option value="">-- All Cars --</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ request('car_id')==$car->id ? 'selected' : '' }}>
                                        {{ $car->car_number }} ({{ $car->car_type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Anomalies List</h5>
            </div>
            <div class="card-body table-responsive">
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Device</th>
                            <th>Sensor Type</th>
                            <th>Value</th>
                            <th>Threshold Min</th>
                            <th>Threshold Max</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($anomalies as $i => $anomaly)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>
                                    {{ $anomaly->device->serial_number ?? '-' }} <br>
                                    <small>Train: {{ $anomaly->device->train->name ?? '-' }}</small> <br>
                                    <small>Car: {{ $anomaly->device->cars->car_number ?? '-' }} ({{ $anomaly->device->cars->car_type ?? '-' }})</small>
                                </td>
                                <td>{{ ucfirst($anomaly->sensor_type) }}</td>
                                <td>{{ $anomaly->value }}</td>
                                <td>{{ $anomaly->min_value ?? '-' }}</td>
                                <td>{{ $anomaly->max_value ?? '-' }}</td>
                                <td>{{ $anomaly->created_at }}</td>
                            </tr>
                        @endforeach
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
    <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
@endsection
