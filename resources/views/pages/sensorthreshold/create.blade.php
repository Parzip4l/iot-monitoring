@extends('layouts.master')
@section('title', 'Add Sensor Threshold')

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Sensor Threshold" title="Add Threshold" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Threshold</h5>
                <a href="{{ route('sensor-threshold.index') }}" class="btn btn-light btn-sm">
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

                <!-- Form Create Threshold -->
                <form action="{{ route('sensor-threshold.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="device_id" class="form-label fw-semibold">Device</label>
                        <select name="device_id" class="form-control" id="device_id" required>
                            <option value="">-- Select Device --</option>
                            @foreach ($devices as $device)
                                <option value="{{ $device->id }}">{{ $device->serial_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sensor_type" class="form-label fw-semibold">Sensor Type</label>
                        <select name="sensor_type" class="form-control" id="sensor_type" required>
                            <option value="">-- Select Sensor Type --</option>
                            <option value="temperature">Temperature</option>
                            <option value="humidity">Humidity</option>
                            <option value="noise">Noise</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="min_value" class="form-label fw-semibold">Min Value</label>
                        <input type="number" step="0.1" name="min_value" id="min_value" 
                               class="form-control @error('min_value') is-invalid @enderror"
                               placeholder="Enter Minimum Value" value="{{ old('min_value') }}">
                        @error('min_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="max_value" class="form-label fw-semibold">Max Value</label>
                        <input type="number" step="0.1" name="max_value" id="max_value" 
                               class="form-control @error('max_value') is-invalid @enderror"
                               placeholder="Enter Maximum Value" value="{{ old('max_value') }}">
                        @error('max_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Add Threshold
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
