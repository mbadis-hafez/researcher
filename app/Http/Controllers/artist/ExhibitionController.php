<?php

namespace App\Http\Controllers\artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExhibitionController extends Controller
{

    public function index() // Removed :View
    {
        $artist = Artist::where("user_id", operator: auth()->user()->id)->first();
        $exhibitions = $artist->exhibitions()->latest()->get()->map(function ($exhibition) {
            $exhibition->short_description = Str::limit($exhibition->description, 220);
            return $exhibition;
        });

        return view("content.apps.artist.artist_profile.artist_exhibition", compact("artist", "exhibitions"));
    }


    public function create(Artist $artist)
    {
        return view('exhibitions.create', compact('artist'));
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
            $data['image'] = $request->file('image')->store('exhibitions', 'public');
        }

        $artist->exhibitions()->create($data);

        return redirect()->route('artists.exhibitions.index', $artist)
            ->with('success', 'Exhibition created successfully');
    }


    public function edit(Artist $artist, Exhibition $exhibition)
    {
        return view('exhibitions.edit', compact('artist', 'exhibition'));
    }

    public function update(Request $request, Artist $artist, Exhibition $exhibition)
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
            if ($exhibition->image) {
                Storage::disk('public')->delete($exhibition->image);
            }
            $data['image'] = $request->file('image')->store('exhibitions', 'public');
        } elseif ($request->filled('current_image')) {
            $data['image'] = $request->current_image;
        }

        $exhibition->update($data);

        return redirect()->route('artists.exhibitions.index', $artist)
            ->with('success', 'Exhibition updated successfully');
    }
    
    public function destroy(Artist $artist, Exhibition $exhibition)
    {
        if ($exhibition->image) {
            Storage::disk('public')->delete($exhibition->image);
        }

        $exhibition->delete();

        return redirect()->route('artists.exhibitions.index', $artist)
            ->with('success', 'Exhibition deleted successfully');
    }
}