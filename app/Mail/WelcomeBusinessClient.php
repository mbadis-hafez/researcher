<?php

namespace App\Mail;

use App\Models\BusinessClient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeBusinessClient extends Mailable
{
    use Queueable, SerializesModels;

    public $client;

    public function __construct(BusinessClient $client)
    {
        $this->client = $client;
    }

    public function build()
    {
        return $this->markdown('emails.business.welcome')
                    ->subject('Welcome to ' . config('app.name'))
                    ->with(['client' => $this->client]);
    }
}