<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BusinessClient;
use Illuminate\Http\Request;

class VerifyBusinessEmailController extends Controller
{
   public function __invoke($id, $hash)
    {
        $client = BusinessClient::findOrFail($id);
        
        // Verify email hash
        if (!hash_equals((string) $hash, sha1($client->email))) {
            abort(403);
        }

        // Mark as verified if not already (without logging in)
        if (!$client->hasVerifiedEmail()) {
            $client->markEmailAsVerified();
            $client->update(['approval_status' => 'pending']); // Optional status field
        }

        // Redirect to verification success page (without authentication)
        return redirect()->route('verification.success')
            ->with([
                'verified' => true,
                'email' => $client->email,
                'status' => 'Your email has been verified successfully!',
                'message' => 'Please wait for administrator approval. You will be notified once your account is activated.'
            ]);
    }
}