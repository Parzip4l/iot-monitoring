@extends('layouts.master')
@section('title', 'Add Cars')

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Train Management" title="Add Cars" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Cars</h5>
                <a href="{{ route('cars.config.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                <!-- Flash Message -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Validation Error -->
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Create Role -->
                <form action="{{ route('cars.config.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="trainName" class="form-label fw-semibold">Train</label>
                        <select name="train_id" class="form-control" id="">
                            <option value="">-- Select Train --</option>
                            @foreach ($train as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="DescriptionTrain" class="form-label fw-semibold">Cars Number</label>
                        <input type="number" name="car_number" id="DescriptionTrain" 
                               class="form-control @error('car_number') is-invalid @enderror"
                               placeholder="Enter Cars Number" value="{{ old('car_number') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="CarsTotal" class="form-label fw-semibold">Cars Type</label>
                        <input type="text" name="car_type" id="CarsTotal" 
                               class="form-control @error('car_type') is-invalid @enderror"
                               placeholder="Enter Cars Type" value="{{ old('car_type') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Add Cars
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
