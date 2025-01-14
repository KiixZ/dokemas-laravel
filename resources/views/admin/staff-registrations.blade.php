@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pengelola Registrations</h5>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($staffRegistrations->isEmpty())
                        <div class="alert alert-info">
                            No staff registration requests found.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($staffRegistrations as $registration)
                                        <tr>
                                            <td>{{ $registration->first_name }} {{ $registration->last_name }}</td>
                                            <td>{{ $registration->email }}</td>
                                            <td>{{ $registration->company }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $registration->id }}">
                                                    View Description
                                                </button>
                                            </td>
                                            <td>
                                                @if($registration->is_approved)
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $registration->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                @if(!$registration->is_approved)
                                                    <form action="{{ route('admin.staff.approve', $registration->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.staff.reject', $registration->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to reject this registration?')">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Description Modal -->
                                        <div class="modal fade" id="descriptionModal{{ $registration->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $registration->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="descriptionModalLabel{{ $registration->id }}">Description</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $registration->description }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table th {
        background-color: #f8f9fa;
    }
    .badge {
        font-weight: 500;
    }
    .btn-link {
        text-decoration: none;
    }
    .btn-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

