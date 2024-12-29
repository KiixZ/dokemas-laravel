@extends('layouts.admin')

@section('title', 'Manage Destinations - Admin Dashboard')

@section('header', 'Manage Destinations')

@section('actions')
<a href="{{ route('admin.destinations.create') }}" class="btn btn-sm btn-primary">
    <i class="fas fa-plus"></i> Add New Destination
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($destinations as $destination)
                        <tr>
                            <td>{{ $destination->id }}</td>
                            <td>{{ $destination->name }}</td>
                            <td>{{ $destination->category->name }}</td>
                            <td>{{ $destination->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this destination?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $destinations->links() }}
</div>
@endsection

