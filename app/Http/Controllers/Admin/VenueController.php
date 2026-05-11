<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::all();
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.index', ['venues' => Venue::all()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'required|string',
        ]);

        Venue::create([
            'name'     => $request->name,
            'address'  => $request->address,
            'city'     => $request->city,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue added successfully!');
    }

    public function edit(Venue $venue)
    {
        $venues = Venue::all();
        return view('admin.venues.index', compact('venue', 'venues'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'required|string',
        ]);

        $venue->update([
            'name'     => $request->name,
            'address'  => $request->address,
            'city'     => $request->city,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        $venue->delete();
        return redirect()->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully!');
    }
}
