@extends('layouts.admin')

@section('title', 'Admin Dashboard - DOKEMAS')

@section('content')
<div class="dashboard-container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $userCount }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $articleCount }}</div>
                <div class="stat-label">Total Articles</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $destinationCount }}</div>
                <div class="stat-label">Total Destinations</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $visitorCount }}</div>
                <div class="stat-label">Total Visitors</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2>QUICK ACTIONS</h2>
        <div class="actions-grid">
            <a href="{{ route('admin.articles.create') }}" class="action-button">
                <i class="fas fa-plus"></i>
                New Article
            </a>
            <a href="{{ route('admin.destinations.create') }}" class="action-button">
                <i class="fas fa-plus"></i>
                New Destination
            </a>
            <a href="{{ route('admin.categories.create') }}" class="action-button">
                <i class="fas fa-plus"></i>
                New Category
            </a>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        padding: 1.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: #000;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 500;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #666;
        font-size: 0.875rem;
    }

    .quick-actions {
        margin-top: 2rem;
    }

    .quick-actions h2 {
        font-size: 0.875rem;
        font-weight: 500;
        color: #666;
        margin-bottom: 1rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 6px;
        color: #000;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .action-button:hover {
        background: #f5f5f5;
        color: #000;
    }

    .action-button i {
        font-size: 0.75rem;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

