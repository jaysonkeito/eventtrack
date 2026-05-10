<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'attendance_id', 'registration_id', 'user_id',
        'event_id', 'certificate_code', 'file_path',
    ];

    public function attendance()   { return $this->belongsTo(Attendance::class); }
    public function registration() { return $this->belongsTo(Registration::class); }
    public function user()         { return $this->belongsTo(User::class); }
    public function event()        { return $this->belongsTo(Event::class); }

    public function getDownloadUrlAttribute(): ?string
    {
        return $this->file_path
            ? asset('storage/certificates/' . $this->file_path)
            : null;
    }
}
