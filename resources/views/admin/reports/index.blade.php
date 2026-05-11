@extends('layouts.app')
@section('title','Reports')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Reports</h1><p class="page-subtitle">Analytics and exportable reports.</p></div>
    <a href="{{ route('admin.reports.export') }}" class="btn-et-primary"><i class="bi bi-download me-1"></i> Export CSV</a>
</div>
<div class="row g-4">
    <div class="col-12">
        <div class="et-card">
            <div class="card-header">Attendance Overview</div>
            <div class="card-body">
                <canvas id="attendanceChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const ctx = document.getElementById('attendanceChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels ?? ['No data']) !!},
        datasets: [{
            label: 'Attended',
            data: {!! json_encode($data ?? [0]) !!},
            backgroundColor: 'rgba(26,86,160,0.7)',
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush
