<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'event_id', 'user_id', 'registration_code', 'qr_code_path', 'status',
    ];

    // ── Relationships ─────────────────────────────────────────
    public function event()      { return $this->belongsTo(Event::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function attendance() { return $this->hasOne(Attendance::class); }
    public function certificate(){ return $this->hasOne(Certificate::class); }

    // ── Helpers ───────────────────────────────────────────────
    public function getQrUrlAttribute(): ?string
    {
        return $this->qr_code_path
            ? asset('storage/qrcodes/' . $this->qr_code_path)
            : null;
    }

    public function getStatusBadgeAttribute(): string
    {
        $map = [
            'pending'   => 'warning',
            'approved'  => 'success',
            'rejected'  => 'danger',
            'cancelled' => 'secondary',
        ];
        $color = $map[$this->status] ?? 'secondary';
        return "<span class=\"badge bg-{$color}\">" . ucfirst($this->status) . "</span>";
    }
}
