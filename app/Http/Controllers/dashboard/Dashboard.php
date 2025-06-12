<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Add this line
use Illuminate\Support\Str;

class Dashboard extends Controller
{
    public function index()
    {

        if (auth()->user()->hasRole("artist")) {

            $artist = Artist::where("user_id", auth()->user()->id)->first();

            return view('content.apps.artist.artist_profile.profile_artist', compact('artist'));
        } else if (auth()->user()->hasRole("user")) {

            if (request()->ajax()) {
                $user = auth()->user();

                // Get artworks with favorite status for the current user
                $artworks = ArtWork::with(['artist', 'category'])
                    ->leftJoin('artwork_user', function ($join) use ($user) {
                        $join->on('artworks.id', '=', 'artwork_user.artwork_id')
                            ->where('artwork_user.user_id', '=', $user ? $user->id : null);
                    })
                    ->select('artworks.*', DB::raw('IF(artwork_user.artwork_id IS NOT NULL, 1, 0) as is_favorited'))
                    ->orderBy('is_favorited', 'desc') // Favorites first
                    ->orderBy('artworks.title', 'asc') // Then sort by title
                    ->get()
                    ->map(function ($artwork) {
                        return [
                            'id' => $artwork->id,
                            'artwork_title' => mb_convert_encoding(Str::limit($artwork->title, 20, '...'), 'UTF-8', 'auto'),
                            'dimensions' => mb_convert_encoding($artwork->dimensions, 'UTF-8', 'auto'),
                            'medium' => mb_convert_encoding($artwork->medium, 'UTF-8', 'auto'),
                            'category' => mb_convert_encoding($artwork->category->name ?? 'N/A', 'UTF-8', 'auto'),
                            'artist_name' => mb_convert_encoding($artwork->artist->name ?? 'N/A', 'UTF-8', 'auto'),
                            'year' => $artwork->year,
                            'artwork_description' => mb_convert_encoding(Str::limit($artwork->description ?? '', 50, '...'), 'UTF-8', 'auto'),
                            'image' => $artwork->display_image,
                            'is_favorited' => (bool)$artwork->is_favorited, // Include favorite status
                        ];
                    });

                return response()->json([
                    'data' => $artworks
                ]);
            }

            return view('content.users.index');
        } else {
            return view('content.dashboard.dashboards-analytics');
        }
    }
}
