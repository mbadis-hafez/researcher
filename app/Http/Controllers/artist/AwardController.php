<?php

namespace App\Http\Controllers\artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AwardController extends Controller
{

    public function index() // Removed :View
    {
        $artist = Artist::where("user_id", operator: auth()->user()->id)->first();
        $awards = $artist->awards()->latest()->get()->map(function ($award) {
            $award->short_description = Str::limit($award->description, 220);
            return $award;
        });

        return view("content.apps.artist.artist_profile.artist_award", compact("artist", "awards"));
    }


    public function create(Artist $artist)
    {
        return view('awards.create', compact('artist'));
    }

    public function store(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $data = $request->all();

        $artist->awards()->create($data);

        return redirect()->route('artists.awards.index', $artist)
            ->with('success', 'award created successfully');
    }


    public function edit(Artist $artist, award $award)
    {
        return view('awards.edit', compact('artist', 'award'));
    }

    public function update(Request $request, Artist $artist, award $award)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $data = $request->all();


        $award->update($data);

        return redirect()->route('artists.awards.index', $artist)
            ->with('success', 'award updated successfully');
    }
    
    public function destroy(Artist $artist, award $award)
    {
        if ($award->image) {
            Storage::disk('public')->delete($award->image);
        }

        $award->delete();

        return redirect()->route('artists.awards.index', $artist)
            ->with('success', 'award deleted successfully');
    }
}