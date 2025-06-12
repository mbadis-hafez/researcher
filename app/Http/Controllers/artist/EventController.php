<?php

namespace App\Http\Controllers\artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{

    public function index() // Removed :View
    {
        $artist = Artist::where("user_id", operator: auth()->user()->id)->first();
        $events = $artist->events()->latest()->get()->map(function ($event) {
            $event->short_description = Str::limit($event->description, 220);
            return $event;
        });

        return view("content.apps.artist.artist_profile.artist_event", compact("artist", "events"));
    }


    public function create(Artist $artist)
    {
        return view('events.create', compact('artist'));
    }

    public function store(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $artist->events()->create($data);

        return redirect()->route('artists.events.index', $artist)
            ->with('success', 'Event created successfully');
    }


    public function edit(Artist $artist, Event $event)
    {
        return view('events.edit', compact('artist', 'event'));
    }

    public function update(Request $request, Artist $artist, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image', 'current_image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        } elseif ($request->filled('current_image')) {
            $data['image'] = $request->current_image;
        }

        $event->update($data);

        return redirect()->route('artists.events.index', $artist)
            ->with('success', 'Event updated successfully');
    }
    
    public function destroy(Artist $artist, Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('artists.events.index', $artist)
            ->with('success', 'Event deleted successfully');
    }
}