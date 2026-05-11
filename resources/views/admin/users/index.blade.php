@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
<div class="page-header">
    <div><h1 class="page-title">Users</h1><p class="page-subtitle">Manage all system users.</p></div>
    <a href="{{ route('admin.users.create') }}" class="btn-et-primary"><i class="bi bi-plus-lg me-1"></i> Add User</a>
</div>

<div class="et-card">
    <div class="card-body p-0">
        <table class="et-table">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="font-weight:600;">{{ $user->full_name }}</td>
                    <td style="font-size:0.82rem;">{{ $user->email }}</td>
                    <td><span class="badge bg-primary-soft">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        @php
                            $statusColors = [
                                'active'   => 'success',
                                'inactive' => 'secondary',
                                'banned'   => 'danger',
                            ];
                            $color = $statusColors[$user->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $color }}">{{ ucfirst($user->status) }}</span>
                    </td>
                    <td style="font-size:0.8rem;">{{ $user->created_at->format('M j, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" data-confirm="Delete this user?">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body pt-0">{{ $users->links() }}</div>
</div>
@endsection
