<?php

namespace App\Http\Controllers\Attendee;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function browse()
    {
        $events = Event::with(['category', 'venue'])
            ->where('status', 'published')
            ->latest()->paginate(12);
        return view('attendee.events.browse', compact('events'));
    }

    public function show(Event $event)
    {
        return view('attendee.events.show', compact('event'));
    }
}
