@extends('layouts.app')
@section('title','Reports')
@section('content')
<div class="page-header"><div><h1 class="page-title">My Event Reports</h1></div></div>
<div class="et-card"><div class="card-header">Attendance Overview</div>
<div class="card-body"><canvas id="attendanceChart" height="100"></canvas></div></div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: { labels: {!! json_encode($labels ?? []) !!}, datasets: [{ label: 'Attended', data: {!! json_encode($data ?? []) !!}, backgroundColor: 'rgba(26,86,160,0.7)', borderRadius: 6 }] },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
@endpush