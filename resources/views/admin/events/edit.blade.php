@extends('layouts.app')
@section('title', 'Edit Event')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Edit Event</h1><p class="page-subtitle">Update event details.</p></div>
    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>
<div class="et-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Event Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $event->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status *</label>
                    <select name="status" class="form-select">
                        @foreach(['draft','published','ongoing','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ old('status',$event->status)==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Organizer *</label>
                    <select name="organizer_id" class="form-select" required>
                        @foreach($organizers as $org)
                            <option value="{{ $org->id }}" {{ old('organizer_id',$event->organizer_id)==$org->id?'selected':'' }}>{{ $org->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">No Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id',$event->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Venue</label>
                    <select name="venue_id" class="form-select">
                        <option value="">No Venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id',$event->venue_id)==$venue->id?'selected':'' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Date & Time *</label>
                    <input type="datetime-local" name="start_datetime" class="form-control" value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date & Time *</label>
                    <input type="datetime-local" name="end_datetime" class="form-control" value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Max Capacity</label>
                    <input type="number" name="max_capacity" class="form-control" value="{{ old('max_capacity', $event->max_capacity) }}" min="1">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Event Type</label>
                    <select name="is_free" class="form-select" id="eventType">
                        <option value="1" {{ old('is_free',$event->is_free)?'selected':'' }}>Free</option>
                        <option value="0" {{ !old('is_free',$event->is_free)?'selected':'' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fee Amount (₱)</label>
                    <input type="number" name="fee_amount" class="form-control" value="{{ old('fee_amount', $event->fee_amount) }}" step="0.01" min="0">
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Banner Image</label>
                    @if($event->banner_image)
                        <div class="mb-2"><img src="{{ asset('storage/'.$event->banner_image) }}" style="height:80px;border-radius:8px;"></div>
                    @endif
                    <input type="file" name="banner_image" class="form-control" accept="image/*">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn-et-primary px-5">Update Event</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
