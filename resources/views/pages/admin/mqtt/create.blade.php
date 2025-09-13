@extends('layouts.master')
@section('title', 'Create Setting')

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Role Management" title="Add Role" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create Setting MQTT</h5>
                <a href="{{ route('mqtt.index') }}" class="btn btn-light btn-sm">
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
                <form action="{{ route('mqtt.store') }}" method="POST">
                    @csrf

                    {{-- Topic --}}
                    <div class="mb-3">
                        <label for="topic" class="form-label fw-semibold">Topic</label>
                        <input type="text" name="topic" id="topic"
                            class="form-control @error('topic') is-invalid @enderror"
                            placeholder="Masukkan topic, contoh: sensor/temp_hum"
                            value="{{ old('topic') }}" required>
                        @error('topic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Device --}}
                    <div class="mb-3">
                        <label for="device_id" class="form-label fw-semibold">Device</label>
                        <select name="device_id" id="device_id"
                                class="form-control @error('device_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Device --</option>
                            @foreach($device as $d)
                                <option value="{{ $d->id }}" {{ old('device_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name ?? $d->device_id }}
                                </option>
                            @endforeach
                        </select>
                        @error('device_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Interval --}}
                    <div class="mb-3">
                        <label for="interval" class="form-label fw-semibold">Interval (detik)</label>
                        <input type="number" name="interval" id="interval"
                            class="form-control @error('interval') is-invalid @enderror"
                            placeholder="Minimal 5 detik"
                            value="{{ old('interval', 5) }}" min="5" required>
                        @error('interval')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Setting
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
