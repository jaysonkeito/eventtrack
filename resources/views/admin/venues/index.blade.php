@extends('layouts.app')
@section('title','Venues')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Venues</h1></div>
    <a href="{{ route('admin.venues.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> Add Venue</a>
</div>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="et-card">
            <div class="card-body p-0">
                <table class="et-table">
                    <thead><tr><th>#</th><th>Name</th><th>City</th><th>Capacity</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($venues as $venue)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:600;">{{ $venue->name }}</td>
                            <td>{{ $venue->city ?? '—' }}</td>
                            <td>{{ $venue->capacity ? number_format($venue->capacity) : 'Unlimited' }}</td>
                            <td>
                                <a href="{{ route('admin.venues.edit', $venue) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.venues.destroy', $venue) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Delete venue?"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No venues yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="et-card">
            <div class="card-header">Add Venue</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.venues.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Venue Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address *</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity') }}" min="1" placeholder="Leave blank for unlimited">
                    </div>
                    <button type="submit" class="btn-et-primary w-100">Add Venue</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
