<?php

namespace App\Http\Controllers\artwork;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\ArtWork;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Also needed for the Str::slug() method

class ArtworkController extends Controller
{

  public function view($id)
  {
    $artwork = Artwork::find($id);

    return view('content.users.view', compact('artwork'));
  }

  public function favorite(Artwork $artwork)
  {
    auth()->user()->favorites()->attach($artwork->id);

    return response()->json([
      'success' => true,
      'message' => 'Artwork added to favorites'
    ]);
  }

  public function unfavorite(Artwork $artwork)
  {
    auth()->user()->favorites()->detach($artwork->id);

    return response()->json([
      'success' => true,
      'message' => 'Artwork removed from favorites'
    ]);
  }

  public function index()
  {
    return view(
      "content.apps.admin.artworks.index"
    );
  }

  public function update(Request $request, $id)
  {
    // Validate request data
    $validated = $request->validate([
      'title' => 'required|string|max:255',
      'dimensions' => 'nullable|string',
      'medium' => 'nullable|string',
      'year_created' => 'nullable|integer',
      'status' => 'required|string',
      'provenance' => 'nullable|string',
      'comment' => 'nullable|string',
      'artist_id' => 'required|exists:artists,id',
      'current_location' => 'nullable|string',
      'source' => 'nullable|string',
      'exhibition' => 'nullable|string',
      'remove_image' => 'nullable|boolean',
      'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      'researcher_id' => 'nullable',
      'researcher_name' => 'nullable|required_if:researcher_id,other|string|max:255'
    ]);

    $artwork = ArtWork::findOrFail($id);

    // Authorization check (example using Laravel's policies)

    DB::beginTransaction();
    try {
      // Handle image
      $imagePath = $this->handleImageUpdate($request, $artwork);

      // Handle researcher data
      $researcherData = $this->handleResearcherData($request);

      // Update the artwork
      $artwork->update(array_merge([
        'title' => $validated['title'],
        'dimensions' => $validated['dimensions'],
        'medium' => $validated['medium'],
        'year' => $validated['year_created'],
        'status' => $validated['status'],
        'provenance' => $validated['provenance'] ?? null,
        'comment' => $validated['comment'] ?? null,
        'artist_id' => $validated['artist_id'],
        'current_location' => $validated['current_location'],
        'source' => $validated['source'],
        'exhibition' => $validated['exhibition'],
        'image_path' => $imagePath,
        'author_id' => Auth::id(),
      ], $researcherData));

      DB::commit();

      return redirect()->route('admin.artworks.index')
        ->with('success', 'Artwork updated successfully!');
    } catch (\Exception $e) {
      DB::rollBack();
      return back()->withErrors(['error' => 'Failed to update artwork: ' . $e->getMessage()]);
    }
  }

  protected function handleImageUpdate(Request $request, ArtWork $artwork): ?string
  {
    $imagePath = $artwork->image_path;

    // Handle image removal
    if ($request->boolean('remove_image')) {
      if ($imagePath && Storage::disk('public')->exists($imagePath)) {
        Storage::disk('public')->delete($imagePath);
      }
      return null;
    }

    // Handle new image upload
    if ($request->hasFile('image_path')) {
      $image = $request->file('image_path');

      if (!$image->isValid()) {
        throw new \Exception("The uploaded image is not valid");
      }

      // Delete old image if it exists
      if ($imagePath && Storage::disk('public')->exists($imagePath)) {
        Storage::disk('public')->delete($imagePath);
      }

      // Generate a unique name for the new image
      $filename = uniqid() . '_' . Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
      $extension = $image->getClientOriginalExtension();
      $filename = "{$filename}.{$extension}";

      // Store the image
      return $image->storeAs('artworks', $filename, 'public');
    }

    return $imagePath;
  }

  protected function handleResearcherData(Request $request): array
  {
    if ($request->researcher_id === 'other') {
      return [
        'researcher_name' => $request->researcher_name,
        'researcher_id' => null
      ];
    }

    return ['researcher_id' => $request->researcher_id];
  }

  public function store(Request $request)
  {
    // Handle image uploads

    if ($request->hasFile('image_path')) {
      $image = $request->image_path;

      try {
        // Generate a unique name for the image
        $filename = uniqid() . '_' . $image->getClientOriginalName();
        // Store the image in the 'artworks' directory inside the 'public' disk
        $path = $image->storeAs('artworks', $filename, 'public');
        $imagePaths = $path;
      } catch (\Exception $e) {
        // Clean up any uploaded images if failure occurs

        Storage::disk('public')->delete($uploadedPath);

        return back()->withErrors(["Failed to upload image " ?? $e->getMessage()]);
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


    $researcherData = [];
    if ($request->researcher_id === 'other') {
      $researcherData['researcher_name'] = $request->researcher_name;
    } else {
      $researcherData['researcher_id'] = $request->researcher_id;
    }

    $artwork = Artwork::create(array_merge([
      'title' => $request->title,
      'dimensions' => $request->dimensions,
      'medium' => $request->medium,
      'year' => $request->year_created,
      'status' => $request->status,
      'provenance' => $request->provenance ?? null,
      'comment' => $request->comment ?? null,
      'image_path' => $imagePaths,
      'additional_info' => json_encode($additionalInfo),
      'artist_id' => $request->artist_id,
      'current_location' => $request->current_location,
      'source' => $request->source,
      'exhibition' => $request->exhibition,
      'status' => $request->status,
      'author_id' => Auth::user()->id,
    ], $researcherData));

    // Redirect with success message
    return redirect()->route('admin.artworks.index')
      ->with('success', 'Artwork created successfully!');
  }
  public function show($id)
  {
  }

  public function edit($id)
  {
    $artwork = ArtWork::where('id', $id)->first();

    $categories = Category::all();
    $artists = Artist::all();
    $researchers = User::role('researcher')->get();

    return view('content.apps.admin.artworks.edit', compact('artwork', 'categories', 'artists', 'researchers'));
  }

  public function create()
  {
    $categories = Category::all();
    $artists = Artist::all();
    $researchers = User::role('researcher')->get();

    return view('content.apps.admin.artworks.create', ['categories' => $categories, 'artists' => $artists, 'researchers' => $researchers]);
  }

  public function getAll()
  {
    $artworks = ArtWork::where('author_id', Auth::user()->id)->get();

    $data = [
      "data" => $artworks->map(function ($artwork) {
        return [
          "id" => $artwork->id,
          "artwork_title" => $artwork->title, // Assuming 'title' is the field name
          "category" => $artwork->category_id ?? 0, // Adjust based on your schema
          "medium" => $artwork->medium ?? 'N/A', // Convert to binary status
          "dimensions" => $artwork->dimensions ?? 'N/A', // Use SKU or fallback to ID
          "price" => "SAR " . number_format($artwork->price, 2), // Format price
          "year" => $artwork->year ?? "N/A",
          "status" => $artwork->status, // Helper method for status
          "image" => $artwork->display_image,
          "artwork_description" => $artwork->description ?? '',
          'author_name' => $artwork->user->name,
          'researcher_name' => $artwork->researcher ? $artwork->researcher->name : $artwork->researcher_name
        ];
      })->toArray()
    ];

    return Response::json($data);
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

      return redirect()->route('admin.artworks.index')
        ->with('success', 'Artwork deleted successfully!');
    } catch (\Exception $e) {
      return redirect()->back()
        ->with('error', 'Failed to delete artwork: ' . $e->getMessage());
    }
  }
}
