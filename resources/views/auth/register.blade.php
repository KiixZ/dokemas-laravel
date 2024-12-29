@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4" style="color: #008080;">Register</h2>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Name">
                            <label for="name">Name</label>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                            <label for="email">Email Address</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                            <label for="password">Password</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            <label for="password-confirm">Confirm Password</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-primary rounded-pill">
                                Register
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <span>Already have an account? </span>
                            <a href="{{ route('login') }}" class="text-decoration-none" style="color: #008080;">Login Here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-floating > .form-control:focus,
    .form-floating > .form-control:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        opacity: .65;
        transform: scale(.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-control {
        border-radius: 0;
        border: none;
        border-bottom: 1px solid #dee2e6;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #008080;
    }

    .btn-outline-primary {
        color: #008080;
        border-color: #008080;
    }

    .btn-outline-primary:hover {
        background-color: #008080;
        border-color: #008080;
    }

    .card {
        background: #fff;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .invalid-feedback {
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>
@endsection

