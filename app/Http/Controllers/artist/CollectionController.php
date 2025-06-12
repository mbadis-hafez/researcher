<?php

namespace App\Http\Controllers\artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\award;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Artwork;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Artist $artist)
    {
        // Ensure the authenticated user owns this artist profile
        $artist = Artist::where("user_id", operator: auth()->user()->id)->first();

        $collections = $artist->collections()
            ->with(['artworks' => function ($query) {
                $query->select('artworks.id', 'title', 'image_path');
            }])
            ->latest()
            ->get();

        $artworks = $artist->artworks()
            ->select('id', 'title', 'image_path')
            ->get();

        return view('content.apps.artist.artist_profile.artist_collection', compact('artist', 'collections', 'artworks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Artist $artist)
    {
        $artist = Artist::find($request->artist);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'artworks' => 'sometimes|array',
            'artworks.*' => 'exists:artworks,id,artist_id,' . $artist->id
        ]);

        $collection = $artist->collections()->create([
            'name' => $validated['name']
        ]);

        if (isset($validated['artworks'])) {
            // Use sync if you want to replace existing relations
            $collection->artworks()->sync($validated['artworks']);

            // Or use attach if you want to add without detaching
            // $collection->artworks()->attach($validated['artworks']);
        }

        return redirect()->back()
            ->with('success', 'Collection created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist, Collection $collection)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'artworks' => 'sometimes|array',
            'artworks.*' => 'exists:artworks,id,artist_id,' . $artist->id
        ]);

        $collection->update(['name' => $validated['name']]);

        // Sync artworks - removes any not in the request, adds new ones
        $collection->artworks()->sync($validated['artworks'] ?? []);

        return redirect()->back()
            ->with('success', 'Collection updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist, Collection $collection)
    {

        $collection->artworks()->detach();
        $collection->delete();

        return redirect()->back()
            ->with('success', 'Collection deleted successfully!');
    }

    /**
     * Get collections for API responses
     */
    public function getCollections(Artist $artist)
    {
        $this->authorize('view', $artist);

        return response()->json([
            'collections' => $artist->collections()
                ->with(['artworks' => function ($query) {
                    $query->select('artworks.id', 'name', 'image');
                }])
                ->get()
        ]);
    }
}
