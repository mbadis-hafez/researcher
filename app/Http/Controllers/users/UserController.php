<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\ArtWork;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Add this line
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
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
                        'medium' => mb_convert_encoding($artwork->medium ?? 'N/A', 'UTF-8', 'auto'),
                        'source' => mb_convert_encoding($artwork->source ?? 'N/A', 'UTF-8', 'auto'),
                        'artist_name' => mb_convert_encoding($artwork->artist->name ?? 'N/A', 'UTF-8', 'auto'),
                        'year' => $artwork->year,
                                                'researcher_name' => $artwork->researcher ? $artwork->researcher->name : $artwork->researcher_name,

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
    }


    public function show()
    {
        $user = auth()->user();
        return view('content.users.profile', compact('user'));
    }


    public function security($id)
    {
        $user = User::find($id);
        $activities = UserActivityLog::where('user_id', $id)->get();
        return view('content.users.security', compact('user', 'activities'));
    }


    public function updatePassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required',
            'newPassword' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[\d]|[^a-zA-Z0-9]).+$/'
            ],
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'newPassword.regex' => 'The password must contain at least one lowercase letter and one number or special character.',
            'confirmPassword.same' => 'The new passwords do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password
        if (!Hash::check($request->currentPassword, auth()->user()->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect');
        }

        // Update password
        auth()->user()->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return redirect()->back()
            ->with('success', 'Password changed successfully!');
    }
}
