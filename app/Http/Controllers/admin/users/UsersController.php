<?php

namespace App\Http\Controllers\admin\users;

use App\Exports\ArtistsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\StoreByBulkArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use App\Models\ArtistImage;
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

class UsersController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = User::with('roles')->get()->map(function ($user) {
                // Handle role with proper capitalization and fallback
                $roleName = 'User'; // Default fallback
                if ($user->roles->isNotEmpty()) {
                    $roleName = $user->roles->first()->name;
                    $roleName = ucfirst(strtolower($roleName)); // Ensure first letter capitalized

                }

                $status = $user->status;
                if (!in_array($status, [1, 2, 3])) {
                    $status = 3; // Default to inactive if invalid
                }
                return [
                    'id' => $user->id,
                    'full_name' => $user->name, // Fallback to example data
                    'username' => $user->username, // Fallback to example
                    'email' => $user->email, // Fallback to example
                    'role' => $roleName,

                    'status' => $status, // Now using the actual status from database
                    'avatar' => $user->avatar ?? '' // Fallback to empty string
                ];
            });

            return response()->json([
                'data' => $users
            ]);
        }

        $roles = Role::all();

        return view('content.apps.admin.users.index', compact('roles'));
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
        ]);

        $user = User::create([
            'name' => $validated['userFullname'],
            'email' => $validated['userEmail'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['userContact'],
            'company' => $validated['companyName'],
        ]);

        // Assign role using Spatie
        $user->assignRole($validated['user-role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return response()->json([
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'company' => $user->company,
            'type' => $user->type,
            'roles' => $user->roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'userFullname' => 'required|string|max:255',
            'userEmail' => 'required|email|unique:users,email,' . $user->id,
            'userContact' => 'nullable|string',
            'companyName' => 'nullable|string',
            'user-role' => 'required|exists:roles,name',
        ];

        // Only validate password if it's provided
        if ($request->password) {
            $rules['password'] = 'string|min:8';
        }

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['userFullname'],
            'email' => $validated['userEmail'],
            'phone' => $validated['userContact'],
            'company' => $validated['companyName'],
            // Only update password if provided
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);

        $user->syncRoles($validated['user-role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }
}
