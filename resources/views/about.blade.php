@extends('layouts.app')

@section('title', 'About Us - Tourism App')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 text-center mb-5">TENTANG DOKEMAS</h1>
            
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('images/logo.svg') }}" alt="Our Team" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Our Mission</h2>
                    <p class="lead">We are passionate about helping travelers discover the world's most amazing destinations. Our team of experienced travel enthusiasts curates the best travel content and destination information to inspire your next adventure.</p>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">Expert Curation</h3>
                            <p class="card-text">Our team of travel experts handpicks the best destinations, accommodations, and experiences to ensure you have an unforgettable journey.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">Community-Driven</h3>
                            <p class="card-text">We believe in the power of shared experiences. Our platform allows travelers to connect, share tips, and inspire each other.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">Sustainable Travel</h3>
                            <p class="card-text">We promote responsible tourism practices to ensure that the destinations we love remain beautiful for generations to come.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-5">
                <h2 class="mb-4">Our Team</h2>
                <p class="lead">Meet the passionate individuals behind Tourism App who work tirelessly to bring you the best travel experiences.</p>
            </div>

            <div class="row">
                @php
                $team_members = [
                    ['name' => 'Rifki Saputra', 'position' => 'Founder & CEO', 'image' => 'team-member-1.png'],
                    ['name' => 'John Smith', 'position' => 'Head of Content', 'image' => 'team-member-2.jpg'],
                    ['name' => 'Emily Brown', 'position' => 'Travel Expert', 'image' => 'team-member-3.jpg'],
                    ['name' => 'Michael Johnson', 'position' => 'Community Manager', 'image' => 'team-member-4.jpg'],
                ];
                @endphp

                @foreach($team_members as $member)
                <div class="col-md-3 mb-4">
                    <div class="card text-center shadow-sm">
                        <img src="{{ asset('storage/images/' . $member['image']) }}" alt="{{ $member['name'] }}" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">{{ $member['name'] }}</h5>
                            <p class="card-text">{{ $member['position'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <h2 class="mb-4">Join Our Community</h2>
                <p class="lead mb-4">Be part of our growing community of travel enthusiasts. Sign up now to get exclusive travel tips, destination guides, and special offers!</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Sign Up Now</a>
            </div>
        </div>
    </div>
</div>
@endsection

