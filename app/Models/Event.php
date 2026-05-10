<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id', 'category_id', 'venue_id', 'title', 'slug',
        'description', 'banner_image', 'start_datetime', 'end_datetime',
        'max_capacity', 'is_free', 'fee_amount', 'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'is_free'        => 'boolean',
        'fee_amount'     => 'decimal:2',
    ];

    // ── Auto-generate slug ────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->slug = Str::slug($event->title) . '-' . time();
        });
    }

    // ── Relationships ─────────────────────────────────────────
    public function organizer()  { return $this->belongsTo(User::class, 'organizer_id'); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function venue()      { return $this->belongsTo(Venue::class); }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function approvedRegistrations()
    {
        return $this->hasMany(Registration::class)->where('status', 'approved');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->where('status', 'approved')->count();
    }

    public function getAttendedCountAttribute(): int
    {
        return $this->attendances()->count();
    }

    public function getIsFull(): bool
    {
        if (is_null($this->max_capacity)) return false;
        return $this->registered_count >= $this->max_capacity;
    }

    public function getStatusBadgeAttribute(): string
    {
        $map = [
            'draft'     => 'secondary',
            'published' => 'primary',
            'ongoing'   => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
        ];
        $color = $map[$this->status] ?? 'secondary';
        return "<span class=\"badge bg-{$color}\">" . ucfirst($this->status) . "</span>";
    }
}
