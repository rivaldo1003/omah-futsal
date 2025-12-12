@extends('layouts.app')

@section('title', 'Login Admin - Omah Futsal Centre')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0 text-center">
                        <i class="fas fa-lock me-2"></i>Login Admin
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>

                    <!-- <div class="mt-3 text-center">
                            <p class="text-muted mb-0">Default Admin Credentials:</p>
                            <p class="mb-0">
                                <strong>Email:</strong> admin@omahfutsal.com<br>
                                <strong>Password:</strong> password
                            </p>
                        </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection