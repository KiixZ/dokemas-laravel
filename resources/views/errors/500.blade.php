@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Error</div>
                <div class="card-body">
                    <h2>Oops! Terjadi kesalahan.</h2>
                    <p>Mohon maaf, telah terjadi kesalahan pada sistem kami. Tim kami sedang bekerja untuk memperbaikinya.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

