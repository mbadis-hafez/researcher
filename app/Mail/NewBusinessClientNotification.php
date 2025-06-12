<?php

namespace App\Mail;

use App\Models\BusinessClient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewBusinessClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $client;

    public function __construct(BusinessClient $client)
    {
        $this->client = $client;
    }

    public function build()
    {
        return $this->markdown('emails.admin.new-business-client')
                    ->subject('New Business Client Registration: ' . $this->client->full_name)
                    ->with(['client' => $this->client]);
    }
}