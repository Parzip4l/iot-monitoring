@extends('layouts.master')
@section('title', 'Edit Device')

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Device Management" title="Edit Device" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Device</h5>
                <a href="{{ route('device.index') }}" class="btn btn-light btn-sm">
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
                <form action="{{ route('device.update', $device->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="deviceName" class="form-label fw-semibold">Device Name</label>
                                <input type="text" name="name" id="deviceName" 
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Enter Device name" value="{{ old('name', $device->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="DeviceCode" class="form-label fw-semibold">Device Code</label>
                                <input type="text" name="serial_number" id="DeviceCode" 
                                    class="form-control @error('serial_number') is-invalid @enderror"
                                    placeholder="Enter Device Code" value="{{ old('serial_number',$device->serial_number) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="DeviceType" class="form-label fw-semibold">Device Type</label>
                                <select name="type" class="form-control" id="">
                                    <option value="1" {{ $device->type == 1 ? 'selected' : '' }}>Humidity and Temperature</option>
                                    <option value="2" {{ $device->type == 2 ? 'selected' : '' }}>Noise</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="IpBroker" class="form-label fw-semibold">Ip Broker</label>
                                <input type="text" name="broker_ip" id="IpBroker" 
                                    class="form-control @error('broker_ip') is-invalid @enderror"
                                    placeholder="Enter IP Broker Device" value="{{ old('broker_ip', $device->broker_ip) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="PortBroker" class="form-label fw-semibold">Ip Broker</label>
                                <input type="text" name="broker_port" id="PortBroker" 
                                    class="form-control @error('broker_port') is-invalid @enderror"
                                    placeholder="Enter Port Broker Device" value="{{ old('broker_port',$device->broker_port) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="DeviceCode" class="form-label fw-semibold">Topic</label>
                                <input type="text" name="topic" id="DeviceCode" 
                                    class="form-control @error('topic') is-invalid @enderror"
                                    placeholder="Enter Device Topic" value="{{ old('topic',$device->topic) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="trainSelect" class="form-label fw-semibold">Train</label>
                                <select name="train_id" id="trainSelect" class="form-control">
                                    <option value="">-- Pilih Train --</option>
                                    @foreach($train as $t)
                                        <option value="{{ $t->id }}" {{ $t->id == $device->train_id ? 'selected' : '' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="carSelect" class="form-label fw-semibold">Cars Number</label>
                                <select name="car_id" id="carSelect" class="form-control">
                                    <option value="">-- Pilih Cars --</option>
                                    @foreach($cars->where('train_id', $device->train_id) as $c)
                                        <option value="{{ $c->id }}" {{ $c->id == $device->car_id ? 'selected' : '' }}>
                                            {{ $c->car_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Update Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const trainSelect = document.getElementById('trainSelect');
    const carSelect = document.getElementById('carSelect');

    trainSelect.addEventListener('change', function() {
        const trainId = this.value;
        carSelect.innerHTML = '<option value="">Loading...</option>';

        if(trainId) {
            fetch(`/settings/train-config/train/${trainId}/cars`)
                .then(res => res.json())
                .then(data => {
                    let options = '<option value="">-- Pilih Cars --</option>';
                    data.forEach(car => {
                        // tandai jika car.id sama dengan device.car_id
                        const selected = car.id == {{ $device->car_id ?? 'null' }} ? 'selected' : '';
                        options += `<option value="${car.id}" ${selected}>${car.car_number}</option>`;
                    });
                    carSelect.innerHTML = options;
                })
                .catch(err => {
                    console.error(err);
                    carSelect.innerHTML = '<option value="">-- Gagal load cars --</option>';
                });
        } else {
            carSelect.innerHTML = '<option value="">-- Pilih Cars --</option>';
        }
    });
});
</script>

@endsection
