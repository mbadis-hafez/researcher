<?php

namespace App\Interfaces;

use App\Models\Artist;

interface ArtistRepositoryInterface
{

    public function getArtists();
    public function getAllArtists();
    public function getArtistById($id): Artist;
    public function deleteArtist($id);
    public function createArtist(array $data): Artist;
    public function updateArtist($id, array $data);
    public function bulkInsert(array $data): bool;
}