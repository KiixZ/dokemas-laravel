@extends('layouts.app')

@section('title', 'About Us - Tourism App')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="display-4 text-center mb-5">TENTANG DOKEMAS</h1>
            
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('public/images/logo.svg') }}" alt="Our Team" class="img-fluid rounded shadow-sm">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Tentang Dokemas</h2>
                    <p class="lead">DOKEMAS adalah sebuah platform yang mampu memberikan informasi kepada wisatawan untuk mencari wisata di wilayah Banyumasan. Dengan dibangunnya sistem berbasis website, diharapkan akan memudahkan wisatawan untuk berwisata ke objek wisata di wilayah Banyumas dan Sekitarnya.</p>
                </div>
            </div>

            <div class="text-center mb-5">
                <h2 class="mb-4">Team Developer</h2>
            </div>

            <div class="row justify-content-center" id="team-members-container">
                @php
                $team_members = [
                    [
                        'name' => 'Rifki Saputra',
                        'position' => 'Fullstuck Developer',
                        'image' => 'gweh.jpg',
                        'github' => 'https://github.com/KiixZ/',
                        'linkedin' => 'https://www.linkedin.com/in/rifkiisaputra/',
                        'instagram' => 'https://instagram.com/rsptrkk'
                    ],
                    
                ];
                @endphp

                @foreach($team_members as $member)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card text-center shadow-sm h-100">
                        <img src="{{ asset('public/images/' . $member['image']) }}" alt="{{ $member['name'] }}" class="card-img-top">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $member['name'] }}</h5>
                            <p class="card-text">{{ $member['position'] }}</p>
                            <div class="social-links mt-auto">
                                <a href="{{ $member['github'] }}" class="social-link" target="_blank" title="GitHub">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="{{ $member['linkedin'] }}" class="social-link" target="_blank" title="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="{{ $member['instagram'] }}" class="social-link" target="_blank" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease-in-out;
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #f8f9fa;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
}

.social-link:hover {
    transform: translateY(-2px);
    background-color: #333;
    color: #fff;
}

.social-link i {
    font-size: 1.2rem;
}

@media (max-width: 767.98px) {
    #team-members-container {
        justify-content: center;
    }
    
    #team-members-container > div {
        width: 80%;
        max-width: 300px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('team-members-container');
    const members = container.children;
    
    if (members.length === 1) {
        members[0].classList.remove('col-lg-3', 'col-md-4', 'col-sm-6');
        members[0].classList.add('col-md-6', 'col-sm-8');
    } else if (members.length === 2) {
        Array.from(members).forEach(member => {
            member.classList.remove('col-lg-3', 'col-md-4');
            member.classList.add('col-md-6');
        });
    } else if (members.length === 3) {
        Array.from(members).forEach(member => {
            member.classList.remove('col-lg-3');
            member.classList.add('col-md-4');
        });
    }
});
</script>
@endsection

