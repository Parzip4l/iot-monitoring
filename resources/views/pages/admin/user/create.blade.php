@extends('layouts.master')
@section('title', 'Create User')

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .password-requirements {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .password-requirements span {
            display: block;
        }
        .valid { color: green; }
        .invalid { color: red; }
    </style>
@endsection

@section('content')
<x-breadcrub pagetitle="LRTJ" subtitle="User Management" title="Create User" />

<div class="container-fluid">
    <div class="page-content-wrapper">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"> Add New User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" id="userForm">
                    @csrf
                    {{-- Full Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" placeholder="Enter full name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" placeholder="Enter email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role_id') is-invalid @enderror"
                                id="role" name="role_id">
                            <option value="" disabled selected>-- Select Role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-btn" data-target="password">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div id="password-reqs" class="password-requirements mt-2">
                            <span id="length" class="invalid">• Min 8 characters</span>
                            <span id="uppercase" class="invalid">• At least 1 uppercase</span>
                            <span id="lowercase" class="invalid">• At least 1 lowercase</span>
                            <span id="number" class="invalid">• At least 1 number</span>
                            <span id="special" class="invalid">• At least 1 special character</span>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            <button type="button" class="btn btn-outline-secondary toggle-btn" data-target="password_confirmation">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div id="matchMessage" class="mt-1"></div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-save"></i> Save User
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
    document.addEventListener("DOMContentLoaded", function() {
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("password_confirmation");
    const matchMessage = document.getElementById("matchMessage");
    const reqBox = document.getElementById("password-reqs");

    const reqs = {
        length: document.getElementById("length"),
        uppercase: document.getElementById("uppercase"),
        lowercase: document.getElementById("lowercase"),
        number: document.getElementById("number"),
        special: document.getElementById("special"),
    };

    function validatePassword() {
        const val = password.value;
        let checks = {
            length: val.length >= 8,
            uppercase: /[A-Z]/.test(val),
            lowercase: /[a-z]/.test(val),
            number: /[0-9]/.test(val),
            special: /[^A-Za-z0-9]/.test(val),
        };

        let allValid = true;
        Object.keys(checks).forEach(key => {
            if (checks[key]) {
                setValid(reqs[key]);
            } else {
                setInvalid(reqs[key]);
                allValid = false;
            }
        });

        // kalau semua valid → sembunyikan box
        if (allValid) {
            reqBox.style.display = "none";
        } else {
            reqBox.style.display = "block";
        }

        return allValid;
    }

    function validateMatch() {
        if (!password.value || !confirmPassword.value) {
            matchMessage.textContent = "";
            return false;
        }
        if (password.value === confirmPassword.value) {
            matchMessage.textContent = "✅ Passwords match";
            matchMessage.className = "text-success";
            return true;
        } else {
            matchMessage.textContent = "❌ Passwords do not match";
            matchMessage.className = "text-danger";
            return false;
        }
    }

    function setValid(el) { el.classList.add("valid"); el.classList.remove("invalid"); }
    function setInvalid(el) { el.classList.add("invalid"); el.classList.remove("valid"); }

    password.addEventListener("input", () => { validatePassword(); validateMatch(); });
    confirmPassword.addEventListener("input", validateMatch);

    // Toggle show/hide password
    document.querySelectorAll(".toggle-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector("i");
            if (target.type === "password") {
                target.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                target.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });

    document.getElementById("userForm").addEventListener("submit", function(e) {
        if (!validatePassword() || !validateMatch()) {
            e.preventDefault();
            alert("⚠️ Password tidak valid atau tidak sama.");
        }
    });
});

</script>
@endsection