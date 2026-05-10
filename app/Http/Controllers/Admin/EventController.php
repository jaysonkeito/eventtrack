<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\Venue;
use App\Models\User;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['organizer', 'category', 'venue'])
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('admin.events.index', compact('events', 'categories'));
    }

    public function create()
    {
        $categories  = Category::all();
        $venues      = Venue::all();
        $organizers  = User::where('role', 'organizer')->get();

        return view('admin.events.create', compact('categories', 'venues', 'organizers'));
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')
                ->store('banners', 'public');
        }

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        $event->load(['organizer', 'category', 'venue', 'registrations.user', 'attendances.user']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        $venues     = Venue::all();
        $organizers = User::where('role', 'organizer')->get();

        return view('admin.events.edit', compact('event', 'categories', 'venues', 'organizers'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        $data = $request->validated();

        if ($request->hasFile('banner_image')) {
            // Delete old banner
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')
                ->store('banners', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate(['status' => 'required|in:draft,published,ongoing,completed,cancelled']);
        $event->update(['status' => $request->status]);

        return back()->with('success', 'Event status updated to ' . ucfirst($request->status) . '.');
    }
}
