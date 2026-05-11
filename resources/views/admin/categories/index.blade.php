{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.app')
@section('title','Categories')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Categories</h1></div>
    <a href="{{ route('admin.categories.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> Add Category</a>
</div>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="et-card">
            <div class="card-body p-0">
                <table class="et-table">
                    <thead><tr><th>#</th><th>Name</th><th>Color</th><th>Events</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td style="font-weight:600;">{{ $cat->name }}</td>
                            <td><span style="display:inline-block;width:20px;height:20px;border-radius:4px;background:{{ $cat->color_hex }};"></span></td>
                            <td>{{ $cat->events_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" data-confirm="Delete this category?"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="et-card">
            <div class="card-header">Add Category</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color_hex" class="form-control form-control-color" value="{{ old('color_hex','#1A56A0') }}">
                    </div>
                    <button type="submit" class="btn-et-primary w-100">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
