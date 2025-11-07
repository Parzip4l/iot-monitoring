        @extends('layouts.master')
        @section('title', 'Log Data Device')
        @section('css')
            <!-- DataTables -->
            <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
            <link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

            <!-- Responsive datatable examples -->
            <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
                type="text/css" />
            <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        @endsection
        @section('content')
        <x-breadcrub pagetitle="LRTJ" subtitle="Analytic" title="Log Data"  />

        <div class="container-fluid">
            <div class="page-content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Device</label>
                                <select name="device_id" class="form-control">
                                    <option value="">-- All Devices --</option>
                                    @foreach($devices as $d)
                                        <option value="{{ $d->serial_number }}" {{ request('device_id') == $d->id ? 'selected' : '' }}>
                                            {{ $d->serial_number }} - {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">From</label>
                                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">To</label>
                                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('log.index') }}" class="btn btn-warning w-100">Reset Filter</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Log Data IOT</h5>
                        <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-download"></i> Download Report
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form action="{{ route('reports.daily.download') }}" method="GET" class="px-3 py-2">
                    <label class="form-label mb-1">Tanggal</label>
                    <input type="date" name="date" class="form-control mb-2" value="{{ now()->toDateString() }}">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Download Harian</button>
                </form>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('reports.monthly.download') }}" method="GET" class="px-3 py-2">
                    <label class="form-label mb-1">Bulan</label>
                    <input type="month" name="month" class="form-control mb-2" value="{{ now()->format('Y-m') }}">
                    <button type="submit" class="btn btn-sm btn-info w-100">Download Bulanan</button>
                </form>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('reports.range.download') }}" method="GET" class="px-3 py-2">
                    <label class="form-label mb-1">Range Tanggal</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="from" class="form-control" required>
                        <input type="date" name="to" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-sm btn-warning w-100 mt-2">Download by Range</button>
                </form>
            </li>
        </ul>
    </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Device Code</th>
                                    <th>Temperature</th>
                                    <th>Humidity</th>
                                    <th>Noise</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log as $i => $l)
                                    <tr>
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ $l->device_id }}</td>
                                        <td>{{ $l->temperature }}</td>
                                        <td>{{ $l->humidity }} %</td>
                                        <td>{{ $l->noise }}</td>
                                        <td>{{ $l->last_saved_at }}</td>
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
            <!-- Buttons examples -->
            <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/jszip/jszip.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
            <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
            <!-- Responsive examples -->
            <script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
            <script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

            <!-- Datatable init js -->
            <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
            <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const deleteButtons = document.querySelectorAll('.btn-delete');
                    
                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function () {
                            let roleId = this.getAttribute('data-id');

                            Swal.fire({
                                title: 'Are you sure?',
                                text: "This role will be permanently deleted!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById(`delete-form-${roleId}`).submit();
                                }
                            })
                        });
                    });
                });
            </script>

        @endsection

