@extends('layouts.app')
@section('title', 'Import Students')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Import Students</h1><p class="page-subtitle">Upload a CSV file to bulk-add students.</p></div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="et-card">
            <div class="card-header"><i class="bi bi-upload me-2"></i>Upload CSV File</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Select CSV File *</label>
                        <input type="file" name="csv_file" class="form-control @error('csv_file') is-invalid @enderror" accept=".csv,.txt" required>
                        @error('csv_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div style="font-size:0.78rem;color:#64748b;margin-top:6px;">
                            Accepted format: .csv | Max size: 5MB
                        </div>
                    </div>
                    <button type="submit" class="btn-et-primary px-5">
                        <i class="bi bi-upload me-1"></i> Upload & Import
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="et-card">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>CSV Format Guide</div>
            <div class="card-body">
                <p style="font-size:0.85rem;color:#64748b;margin-bottom:12px;">
                    Your CSV file must follow this column order:
                </p>
                <div style="background:#F8FAFC;border-radius:8px;padding:14px;font-family:monospace;font-size:0.78rem;overflow-x:auto;border:1px solid #e2e8f0;">
                    <div style="color:#1A56A0;font-weight:700;margin-bottom:6px;">student_id, first_name, last_name, year_level, college, program, email</div>
                    <div style="color:#64748b;">2021-00123, Juan, Dela Cruz, 1st Year, College of Arts and Sciences, Bachelor of Science in Information Technology, juan.delacruz@norsu.edu.ph</div>
                </div>
                <div style="margin-top:14px;">
                    <div style="font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:8px;">Notes:</div>
                    <ul style="font-size:0.78rem;color:#64748b;padding-left:18px;margin:0;">
                        <li>First row should be the header (it will be skipped)</li>
                        <li>Email column is optional — if blank, a default email will be generated</li>
                        <li>Default password will be the Student ID</li>
                        <li>Duplicate Student IDs or emails will be skipped</li>
                        <li>Students are automatically set as <strong>Attendee</strong> role</li>
                    </ul>
                </div>
                <div style="margin-top:16px;">
                    <a href="data:text/csv;charset=utf-8,student_id,first_name,last_name,year_level,college,program,email%0A2021-00123,Juan,Dela Cruz,1st Year,College of Arts and Sciences,Bachelor of Science in Information Technology,juan@norsu.edu.ph"
                       download="norsu_students_template.csv"
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-download me-1"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
