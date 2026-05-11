<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())
            ->withCount(['registrations', 'attendances'])
            ->latest()->paginate(10);
        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        $venues     = Venue::all();
        return view('organizer.events.create', compact('categories', 'venues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:200',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after:start_datetime',
            'status'         => 'required|in:draft,published',
        ]);

        $data = $request->all();
        $data['organizer_id'] = auth()->id();
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        Event::create($data);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        $this->authorize_event($event);
        $categories = Category::all();
        $venues     = Venue::all();
        return view('organizer.events.edit', compact('event', 'categories', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize_event($event);
        $request->validate([
            'title'          => 'required|string|max:200',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after:start_datetime',
        ]);

        $data = $request->all();
        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) Storage::disk('public')->delete($event->banner_image);
            $data['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $event->update($data);
        return redirect()->route('organizer.events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $this->authorize_event($event);
        $event->delete();
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted!');
    }

    public function publish(Event $event)
    {
        $this->authorize_event($event);
        $event->update(['status' => 'published']);
        return back()->with('success', 'Event published successfully!');
    }

    private function authorize_event(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
