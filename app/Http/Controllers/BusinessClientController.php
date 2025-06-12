<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBusinessClientRequest;
use App\Mail\NewBusinessClientNotification;
use App\Mail\WelcomeBusinessClient;
use App\Models\BusinessClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BusinessClientController extends Controller
{



    public function store(StoreBusinessClientRequest $request)
    {
        $validated = $request->validated();

        $client = BusinessClient::create([
            'full_name' => $validated['username'],
            'email' => $validated['email'],
            'job_title' => $validated['job'],
            'phone' => $validated['phone'],
            'business_type' => $validated['business_type'],
            'organization' => $validated['organization'],
            'specific_business_type' => $validated['business_type'] === 'other'
                ? $validated['other_business_type']
                : null,
            'terms_accepted' => true
        ]);

        try {
            // Send verification email
            $client->sendEmailVerificationNotification();

            // Send welcome email
            Mail::to($client->email)->send(new WelcomeBusinessClient($client));

            // Notify admin
          //  Mail::to(config('mail.admin_address'))->send(new NewBusinessClientNotification($client));
        } catch (\Exception $e) {
            Log::error('Client registration failed: ' . $e->getMessage(), [
                'client_id' => $client->id,
                'error' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Registration completed, but some emails failed to send.')
                ->withInput();
        }

        return redirect()->back()
            ->with('success', 'Registration complete! Please check your email to verify your account.');
    }
}
