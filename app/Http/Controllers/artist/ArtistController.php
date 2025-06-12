<?php

namespace App\Http\Controllers\artist;

use App\Exports\ArtistsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\StoreByBulkArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use App\Models\ArtistImage;
use App\Repositories\ArtistRepository;
use App\Services\ArtistImportService;
use App\Traits\FileManagerTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Nnjeim\World\Models\Country;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArtistController extends Controller
{
    use FileManagerTrait;

    public function __construct(private ArtistRepository $artistRepository) {}

    public function profile()
    {
        if (auth()->user()->hasRole("artist")) {
            $artist = Artist::where("user_id", operator: auth()->user()->id)->first();

            $countries = countries();
            return view('content.apps.artist.artist_profile.profile_artist', compact('artist', 'countries'));
        }
    }

    public function index() // Removed :View
    {
        $artists = $this->artistRepository->getArtists();


        // Get statistics for both active and pending artists
        $totalArtists = $artists->count();
        $activeArtists = 0;
        $pendingArtists = 0;
        $percentageChange = 0 ;
        $pendingPercentageChange =0;


        if (request()->ajax()) {
            return response()->json([
                'data' => ArtistResource::collection($artists)
            ]);
        }

        return view('content.apps.artist.index', [
            'artists' => $artists,
            'stats' => [
                'total_artists' => $totalArtists,
                'active_artists' => $activeArtists,
                'percentage_change' => $percentageChange,
                'pending_artists' => $pendingArtists,
                'pending_percentage_change' => $pendingPercentageChange,
                'time_period' => 'Last week analytics'
            ]
        ]);
    }

    private function calculatePercentageChange()
    {
        // Implement your logic to calculate percentage change
        // For example, compare current week with previous week
        $currentWeekCount = Artist::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $lastWeekCount = Artist::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        if ($lastWeekCount == 0) return 0; // prevent division by zero

        return round((($currentWeekCount - $lastWeekCount) / $lastWeekCount) * 100);
    }

    private function calculatePendingPercentageChange()
    {
        $currentWeekPending = Artist::where('status', '1')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $lastWeekPending = Artist::where('status', '1')
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();

        if ($lastWeekPending == 0) return 0; // prevent division by zero

        return round((($currentWeekPending - $lastWeekPending) / $lastWeekPending) * 100);
    }

    public function show($id) {}

    public function create(): View
    {
        // $countries = DB::table('countries')->get();
        $countries = countries();
        return view('content.apps.artist.create', ['countries' => $countries]);
    }

    public function createWithBulk(): View
    {
        return view('content.apps.artist.create-with-bulk');
    }

    public function store(StoreArtistRequest $request): RedirectResponse
    {
        // Get validated data from the request
        $validated = $request->validated();

        // Create new artist (assuming you have an Artist model)
        Artist::create([
            'name' => $validated['name'],
            // add other fields if needed
        ]);

        return redirect()->route('app-artist-list')->with('success', trans('Artist saved successfully'));
    }

    public function storeByBulk(StoreByBulkArtistRequest $request, ArtistImportService $artistImportService): RedirectResponse
    {
        try {
            $artistImportService->import($request->file('artists_file'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return back()->with('success', trans('Artists saved successfully'));
    }

    public function edit($id): View
    {
        $artist = $this->artistRepository->getArtistById($id);
        // $countries = DB::table('countries')->get();
        $countries = countries();
        return view('content.apps.artist.edit', ['artist' => $artist, 'countries' => $countries]);
    }

    public function update(UpdateArtistRequest $request, $id): RedirectResponse
    {
        $this->artistRepository->updateArtist($id, $request->all());

        return redirect()->back()->with('success', trans('Artist updated successfully'));
    }

    public function destroy($id): RedirectResponse
    {
        $this->artistRepository->deleteArtist($id);

        return redirect()->route('app-artist-list')->with('success', trans('Artist deleted successfully'));
    }

    public function exportList(Request $request): BinaryFileResponse|RedirectResponse
    {
        $artists = $this->artistRepository->getAllArtists();
        $data = [
            'artists' => $artists,
        ];

        return Excel::download(new ArtistsExport($data), 'artists-list-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function uploadAdditionalImages(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image'
        ], [
            'file.image' => trans('the field must be a valid image')
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        ArtistImage::create([
            'name' => $request->file->getClientOriginalName(),
            'path' => $this->upload('artist/additional-images/', $request->file->getClientOriginalExtension(), $request->file)
        ]);
        return response()->json(['success' => true, 'fileName' => $request->file->getClientOriginalName()]);
    }
}
