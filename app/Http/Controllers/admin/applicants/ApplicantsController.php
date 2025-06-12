<?php

namespace App\Http\Controllers\admin\applicants;

use App\Exports\ArtistsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\StoreByBulkArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use App\Models\ArtistImage;
use App\Models\BusinessClient;
use App\Models\User;
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
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicantsController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = BusinessClient::all()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'full_name' => $user->full_name, // Fallback to example data
                    'job_title' => $user->job_title, // Fallback to example
                    'email' => $user->email, // Fallback to example
                    'phone' => $user->phone ?? '', // Hardcoded as per example
                    'specific_business_type' => $user->specific_business_type ?? '', // Hardcoded as per example
                    'business_type' => $user->business_type,
                    'avatar' => $user->avatar ?? '', // Fallback to empty string.
                    'verified_at' => $user->email_verified_at != null ? 'verified' : 'pending'
                ];
            });

            return response()->json([
                'data' => $users
            ]);
        }


        return view('content.apps.admin.applicants.index');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'userFullname' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'userContact' => 'nullable|string',
            'companyName' => 'nullable|string',
            'user-role' => 'required|exists:roles,name',
            'user-type' => 'nullable|string'
        ]);

        $user = User::create([
            'name' => $validated['userFullname'],
            'email' => $validated['userEmail'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['userContact'],
            'company' => $validated['companyName'],
            'type' => $validated['user-type']
        ]);

        // Assign role using Spatie
        $user->assignRole($validated['user-role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit($id)
    {

        $user = BusinessClient::find($id);
        return response()->json([
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'organization' => $user->organization,
            'job_title' => $user->job_title,
            'business_type' => $user->business_type,
            'specific_business_type' => $user->specific_business_type
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'userFullname' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users,email,' . $user->id,
            'userContact' => 'nullable|string',
            'organization' => 'nullable|string',
            'job_title' => 'nullable|string',
            'business_type' => 'required|string|in:hotel_hospitality,architecture_firm,interior_design,corporate_office,restaurant_group,healthcare,education,other',
            'is_approved' => 'nullable|boolean'
        ];

        if ($request->business_type === 'other') {
            $rules['specific_business_type'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);


        $user = User::create([
        'name' => $validated['userFullname'],
        'email' => $validated['userEmail'],
        'phone' => $validated['userContact'],
        'company' => $request['organization'],
        'type' => $request['business_type'],
        'password' => Hash::make(Str::random(10)),
        ]);

        $user->assignRole('user');

        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }
}
