<tr>
    <td>{{ $loop->iteration }}</td>
    <td style="font-family:monospace;font-size:0.82rem;color:#374151;">{{ $user->student_id ?? '—' }}</td>
    <td>
        <div style="font-weight:600;color:#0f172a;">{{ $user->full_name }}</div>
        <div style="font-size:0.75rem;color:#64748b;">{{ $user->email }}</div>
    </td>
    <td style="font-size:0.78rem;">
        @if($user->college)
            <div style="color:#1A56A0;font-weight:500;">{{ $user->college }}</div>
            <div style="color:#64748b;">{{ $user->program ? \Str::limit($user->program, 38) : '' }}</div>
        @else
            <span style="color:#cbd5e1;">—</span>
        @endif
    </td>
    <td style="font-size:0.82rem;">{{ $user->year_level ?? '—' }}</td>
    <td>
        @php
            $roleColors = ['admin'=>'purple','organizer'=>'info','attendee'=>'primary'];
            $rc = $roleColors[$user->role] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $rc }}-soft" style="font-size:0.75rem;">{{ ucfirst($user->role) }}</span>
    </td>
    <td>
        @php $sc = ['active'=>'success','inactive'=>'warning','banned'=>'danger'][$user->status] ?? 'secondary'; @endphp
        <span class="badge bg-{{ $sc }}">{{ ucfirst($user->status) }}</span>
    </td>
    <td>
        @if($user->role === 'organizer' && $user->status === 'inactive')
            <form method="POST" action="{{ route('admin.users.approve', $user) }}" style="display:inline;">
                @csrf @method('PATCH')
                <button class="btn btn-sm btn-success me-1" title="Approve Organizer">
                    <i class="bi bi-check-lg"></i> Approve
                </button>
            </form>
        @endif
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
            <i class="bi bi-pencil"></i>
        </a>
        @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" data-confirm="Delete {{ $user->full_name }}?">
                <i class="bi bi-trash"></i>
            </button>
        </form>
        @endif
    </td>
</tr>
