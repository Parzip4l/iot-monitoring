@extends('layouts.master')
@section('title')
    Editable Table
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-autoFill-bs4/css/autoFill.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/datatables.net-keytable-bs4/css/keyTable.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')

    <x-breadcrub pagetitle="Morvin" subtitle="Tables" title="Editable Table"  />

    <div class="container-fluid">
        <div class="page-content-wrapper">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="header-title">Datatable Editable</h4>
                            <p class="card-title-desc">Table Edits is a lightweight jQuery plugin for making table rows
                                editable.</p>

                            <div class="table-responsive">
                                <table class="table table-editable table-nowrap align-middle table-edits">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-id="1">
                                            <td data-field="id" style="width: 80px">1</td>
                                            <td data-field="name">David McHenry</td>
                                            <td data-field="age">24</td>
                                            <td data-field="gender">Male</td>
                                            <td style="width: 100px">
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-id="2">
                                            <td data-field="id">2</td>
                                            <td data-field="name">Frank Kirk</td>
                                            <td data-field="age">22</td>
                                            <td data-field="gender">Male</td>
                                            <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-id="3">
                                            <td data-field="id">3</td>
                                            <td data-field="name">Rafael Morales</td>
                                            <td data-field="age">26</td>
                                            <td data-field="gender">Male</td>
                                            <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-id="4">
                                            <td data-field="id">4</td>
                                            <td data-field="name">Mark Ellison</td>
                                            <td data-field="age">32</td>
                                            <td data-field="gender">Male</td>
                                            <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr data-id="5">
                                            <td data-field="id">5</td>
                                            <td data-field="name">Minnie Walter</td>
                                            <td data-field="age">27</td>
                                            <td data-field="gender">Female</td>
                                            <td>
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->

        </div>
    </div> <!-- container-fluid -->
@endsection
@section('scripts')
    <!-- Table Editable plugin -->
    <script src="{{ URL::asset('build/libs/table-edits/build/table-edits.min.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/table-editable.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
