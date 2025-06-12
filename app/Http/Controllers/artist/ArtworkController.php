<?php

namespace App\Http\Controllers\artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtWork;
use App\Models\ArtWorkView;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class ArtworkController extends Controller
{

    public function index()
    {
        $artist = Artist::where("user_id", auth()->user()->id)->first();

        // Existing exhibitions logic
        $exhibitions = $artist->exhibitions()->latest()->get()->map(function ($exhibition) {
            $exhibition->short_description = Str::limit($exhibition->description, 220);
            return $exhibition;
        });

        // Dashboard stats logic
        $artistId = $artist->id;
        $stats = [
            // Basic counts
            'totalArtworks' => ArtWork::where('artist_id', $artistId)->count(),
            'activeArtworks' => ArtWork::where('artist_id', $artistId)->where('is_active', true)->count(),
            'totalCategories' => ArtWork::where('artist_id', $artistId)->distinct('category_id')->count('category_id'),

            // Featured artworks
            'featuredCount' => ArtWork::where('artist_id', $artistId)
                ->where('is_featured', true)
                ->count(),
            'featuredViews' => ArtWorkView::whereHas('artwork', fn($q) => $q->where('artist_id', $artistId))
                ->count(),

            // Recent activity
            'recentUploads' => ArtWork::where('artist_id', $artistId)
                ->where('created_at', '>', now()->subDays(7))
                ->count(),

            // Popular category
            'mostPopularCategory' => Category::find(
                ArtWork::where('artist_id', $artistId)
                    ->groupBy('category_id')
                    ->selectRaw('category_id, count(*) as total')
                    ->orderByDesc('total')
                    ->first()
                    ?->category_id
            )?->name ?? 'N/A',

            // Growth rates
            'artworkGrowth' => $this->calculateGrowth('artworks', $artistId),
            'uploadTrend' => $this->calculateUploadTrend($artistId)
        ];

        return view(
            "content.apps.artist.artist_profile.artist_artwork",
            compact("artist", "exhibitions", "stats")
        );
    }

    private function calculateGrowth($type, $artistId)
    {
        // Simple month-over-month growth calculation
        $current = ArtWork::where('artist_id', $artistId)
            ->whereMonth('created_at', now()->month)
            ->count();

        $previous = ArtWork::where('artist_id', $artistId)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        return $previous > 0 ? round(($current - $previous) / $previous * 100) : 100;
    }

    private function calculateUploadTrend($artistId)
    {
        $current = ArtWork::where('artist_id', $artistId)
            ->where('created_at', '>', now()->subDays(7))
            ->count();

        $previous = ArtWork::where('artist_id', $artistId)
            ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
            ->count();

        return $previous > 0 ? round(($current - $previous) / $previous * 100) : 100;
    }
    public function getAll()
    {
        $artworks = ArtWork::where("artist_id", auth()->user()->artist->id)->get();

        
        $data = [
            "data" => $artworks->map(function ($artwork) {
                return [
                    "id" => $artwork->id,
                    "artwork_title" => $artwork->title, // Assuming 'title' is the field name
                    "medium" => $artwork->medium ?? 'N/A', // Convert to binary status
                    "dimensions" => $artwork->dimensions ?? 'N/A', // Use SKU or fallback to ID
                    "year" => $artwork->year ?? "N/A",
                    "status" =>$artwork->status, // Helper method for status
                    "image" => $artwork->display_image,
                    "artwork_description" => $artwork->description ?? '',
                    'researcher_name'=>$artwork->researcher ? $artwork->researcher->name : $artwork->researcher_name
                ];
            })->toArray()
        ];

        return Response::json($data);
    }

    // Helper method to convert artwork status to your numeric values
    

    public function create()
    {
        $categories = Category::all();

        return view('content.apps.artist.artworks.create', ['categories' => $categories]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'dimensions' => 'required|string|max:100',
            'medium' => 'required|string|max:100',
            'year_created' => 'required|integer|min:1000|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'status' => 'required|integer|in:1,2,3,4,5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_url' => 'nullable|url',
            'condition' => 'nullable|string|in:Excellent,Good,Fair,Poor',
            'provenance' => 'nullable|string',
            'comment' => 'nullable|string',
            'additions' => 'nullable|array',
            'additions_value' => 'nullable|array',
            'condition_report' => 'nullable|string',
            'current_location' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            
        ]);

        // Handle image uploads
        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (!$image->isValid()) {
                    return back()->withErrors(["Image #" . ($index + 1) . " is not valid"]);
                }

                try {

                    // Generate a unique name for the image
                    $filename = uniqid() . '_' . $image->getClientOriginalName();
                    // Store the image in the 'artworks' directory inside the 'public' disk
                    $path = $image->storeAs('artworks', $filename, 'public');
                    $imagePaths[] = $path;
                } catch (\Exception $e) {
                    // Clean up any uploaded images if failure occurs
                    foreach ($imagePaths as $uploadedPath) {
                        Storage::disk('public')->delete($uploadedPath);
                    }
                    return back()->withErrors(["Failed to upload image #" . ($index + 1) . ": " . $e->getMessage()]);
                }
            }
        }

        // Ensure at least one image was uploaded
        if (empty($imagePaths)) {
            return back()->withError('Please upload at least one image');
        }

        // Process additional options
        $additionalInfo = [];

        if ($request->has('group-a')) {
            foreach ($request->input('group-a') as $item) {
                if (!empty($item['additions']) && isset($item['additions_value'])) {
                    $additionalInfo[] = [
                        'option' => $item['additions'],
                        'value' => $item['additions_value']
                    ];
                }
            }
        }

        // Or if you prefer key-value pairs:
        $keyValuePairs = [];
        if ($request->has('group-a')) {
            foreach ($request->input('group-a') as $item) {
                if (!empty($item['additions']) && isset($item['additions_value'])) {
                    $keyValuePairs[$item['additions']] = $item['additions_value'];
                }
            }
        }

        // Create the artwork
        $artwork = Artwork::create([
            'title' => $validatedData['title'],
            'dimensions' => $validatedData['dimensions'],
            'medium' => $validatedData['medium'],
            'year' => $validatedData['year_created'],
            'price' => $validatedData['price'],
            'status' => $validatedData['status'],
            'condition' => $validatedData['condition'] ?? 'Good',
            'provenance' => $validatedData['provenance'] ?? null,
            'comment' => $validatedData['comment'] ?? null,
            'images' => json_encode($imagePaths),
            'image_path' => '',
            'additional_info' => json_encode(value: $additionalInfo),
            'artist_id' => optional(auth()->user()->artist)->id,
            'condition_report' => $validatedData['condition_report'] ?? null,
            'current_location' => $validatedData['current_location'],
            'category_id' => $validatedData['category_id'],


        ]);

        // Redirect with success message
        return redirect()->route('artist-artworks')
            ->with('success', 'Artwork created successfully!');
    }


    public function edit($id)
    {
        $artwork = ArtWork::where('id', $id)->where('artist_id', auth()->user()->artist->id)->first();

        $categories = Category::all();

        return  view('content.apps.artist.artworks.edit', compact('artwork', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $artwork = ArtWork::find($id);

        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'dimensions' => 'required|string|max:100',
            'medium' => 'required|string|max:100',
            'year_created' => 'required|integer|min:1000|max:' . date('Y'),
            'price' => 'required|numeric|min:0',
            'status' => 'required|integer|in:1,2,3,4,5',
            'image_url' => 'nullable|url',
            'condition' => 'nullable|string|in:Excellent,Good,Fair,Poor',
            'provenance' => 'nullable|string',
            'comment' => 'nullable|string',
            'additions' => 'nullable|array',
            'additions_value' => 'nullable|array',
            'condition_report' => 'nullable|string',
            'removed_images' => 'nullable|array',
            'current_location' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',

        ]);

        // Handle image uploads
        $imagePaths = $artwork->images ? json_decode($artwork->images, true) : [];

        // Remove deleted images
        if ($request->has('removed_images')) {
            foreach ($request->input('removed_images') as $removedImage) {
                // Remove from storage
                Storage::disk('public')->delete($removedImage);

                // Remove from array
                $imagePaths = array_filter($imagePaths, function ($path) use ($removedImage) {
                    return $path !== $removedImage;
                });
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (!$image->isValid()) {
                    return back()->withErrors(["Image #" . ($index + 1) . " is not valid"]);
                }

                try {
                    // Generate a unique name for the image
                    $filename = uniqid() . '_' . $image->getClientOriginalName();
                    // Store the image in the 'artworks' directory inside the 'public' disk
                    $path = $image->storeAs('artworks', $filename, 'public');
                    $imagePaths[] = $path;
                } catch (\Exception $e) {
                    // Clean up any uploaded images if failure occurs
                    foreach ($imagePaths as $uploadedPath) {
                        if (!in_array($uploadedPath, json_decode($artwork->images, true) ?? [])) {
                            Storage::disk('public')->delete($uploadedPath);
                        }
                    }
                    return back()->withErrors(["Failed to upload image #" . ($index + 1) . ": " . $e->getMessage()]);
                }
            }
        }

        // Handle image URL if provided
        if ($request->filled('image_url')) {
            try {
                $url = $request->input('image_url');
                $contents = file_get_contents($url);
                $name = substr($url, strrpos($url, '/') + 1);
                $filename = uniqid() . '_' . $name;
                Storage::disk('public')->put('artworks/' . $filename, $contents);
                $imagePaths[] = 'artworks/' . $filename;
            } catch (\Exception $e) {
                return back()->withErrors(["Failed to download image from URL: " . $e->getMessage()]);
            }
        }

       
        // Process additional options
        $additionalInfo = [];
        if ($request->has('group-a')) {
            foreach ($request->input('group-a') as $item) {
                if (!empty($item['additions']) && isset($item['additions_value'])) {
                    $additionalInfo[] = [
                        'option' => $item['additions'],
                        'value' => $item['additions_value']
                    ];
                }
            }
        }

        // Update the artwork
        $artwork->update([
            'title' => $validatedData['title'],
            'dimensions' => $validatedData['dimensions'],
            'medium' => $validatedData['medium'],
            'year' => $validatedData['year_created'],
            'price' => $validatedData['price'],
            'category_id' => $validatedData['category_id'],
            'status' => $validatedData['status'],
            'condition' => $validatedData['condition'] ?? 'Good',
            'provenance' => $validatedData['provenance'] ?? null,
            'comment' => $validatedData['comment'] ?? null,
            'images' => json_encode(array_values($imagePaths)), // Reindex array
            'additional_info' => json_encode($additionalInfo),
            'condition_report' => $validatedData['condition_report'] ?? null,
            'current_location' => $validatedData['current_location'],

        ]);

        // Redirect with success message
        return redirect()->route('artist-artworks')
            ->with('success', 'Artwork updated successfully!');
    }

    public function destroy($id)
    {
        $artwork = ArtWork::find($id);
        try {
            // Delete associated images from storage
            if ($artwork->images) {
                $images = json_decode($artwork->images, true);
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            // Delete the main image if it exists
            if ($artwork->image_path) {
                Storage::disk('public')->delete($artwork->image_path);
            }

            // Delete the artwork record
            $artwork->delete();

            return redirect()->route('artist-artworks')
                ->with('success', 'Artwork deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete artwork: ' . $e->getMessage());
        }
    }
}
