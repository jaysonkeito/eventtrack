@extends('layouts.app')
@section('title','Create Event')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Create Event</h1></div>
    <a href="{{ route('organizer.events.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="et-card"><div class="card-body">
<form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Event Title *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-4"><label class="form-label">Status</label>
            <select name="status" class="form-select"><option value="draft">Draft</option><option value="published">Published</option></select></div>
        <div class="col-md-4"><label class="form-label">Category</label>
            <select name="category_id" class="form-select"><option value="">None</option>
                @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Venue</label>
            <select name="venue_id" class="form-select"><option value="">None</option>
                @foreach($venues as $v)<option value="{{ $v->id }}">{{ $v->name }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Max Capacity</label>
            <input type="number" name="max_capacity" class="form-control" min="1" placeholder="Unlimited"></div>
        <div class="col-md-6"><label class="form-label">Start Date & Time *</label>
            <input type="datetime-local" name="start_datetime" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">End Date & Time *</label>
            <input type="datetime-local" name="end_datetime" class="form-control" required></div>
        <div class="col-12"><label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="col-12"><label class="form-label">Banner Image</label>
            <input type="file" name="banner_image" class="form-control" accept="image/*"></div>
        <div class="col-12"><button type="submit" class="btn-et-primary px-5">Create Event</button></div>
    </div>
</form>
</div></div>
@endsection