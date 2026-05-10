<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'registration_id', 'event_id', 'user_id',
        'scanned_by', 'time_in', 'time_out', 'scan_method', 'notes',
    ];

    protected $casts = [
        'time_in'  => 'datetime',
        'time_out' => 'datetime',
    ];

    public function registration() { return $this->belongsTo(Registration::class); }
    public function event()        { return $this->belongsTo(Event::class); }
    public function user()         { return $this->belongsTo(User::class); }
    public function scannedBy()    { return $this->belongsTo(User::class, 'scanned_by'); }
    public function certificate()  { return $this->hasOne(Certificate::class); }
}
