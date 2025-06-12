<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->name,
            'email' => $this->email,
            'role' => "Artist",
            'artworks' => $this->artworks->count(),
            'status' => $this->status,
            'avatar' => ""
            // ... include all other fields
        ];
    }
}
