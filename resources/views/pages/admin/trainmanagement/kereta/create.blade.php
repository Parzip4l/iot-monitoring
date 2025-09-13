@extends('layouts.master')
@section('title', 'Add Train')

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="Train Management" title="Add Train" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add New Train</h5>
                <a href="{{ route('train.config.index') }}" class="btn btn-light btn-sm">
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
                <form action="{{ route('train.config.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="trainName" class="form-label fw-semibold">Train Name</label>
                        <input type="text" name="name" id="trainName" 
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Enter Train name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="DescriptionTrain" class="form-label fw-semibold">Train Decription</label>
                        <input type="text" name="description" id="DescriptionTrain" 
                               class="form-control @error('description') is-invalid @enderror"
                               placeholder="Enter Device Code" value="{{ old('description') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="CarsTotal" class="form-label fw-semibold">Total Cars</label>
                        <input type="text" name="total_gerbong" id="CarsTotal" 
                               class="form-control @error('total_gerbong') is-invalid @enderror"
                               placeholder="Enter Total Cars" value="{{ old('total_gerbong') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Add Train
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
