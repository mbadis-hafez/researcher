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
    return redirect()->route('admin.artworks.index')
      ->with('success', 'Artwork updated successfully!');
  }
  public function store(Request $request)
  {
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
      'image_path' => json_encode($imagePaths),
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
  public function show($id) {}

  public function edit($id)
  {
    $artwork = ArtWork::where('id', $id)->first();

    $categories = Category::all();
    $artists = Artist::all();
    $researchers = User::role('researcher')->get();

    return  view('content.apps.admin.artworks.edit', compact('artwork', 'categories', 'artists','researchers'));
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
    $artworks = ArtWork::where('author_id',Auth::user()->id)->get();

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
          'researcher_name'=>$artwork->researcher ? $artwork->researcher->name : $artwork->researcher_name
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
