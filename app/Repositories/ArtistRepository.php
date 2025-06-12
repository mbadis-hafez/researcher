<?php

namespace App\Repositories;

use App\Interfaces\ArtistRepositoryInterface;
use App\Models\Artist;
use App\Models\ArtistImage;
use App\Models\User;
use App\Traits\FileManagerTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ArtistRepository implements ArtistRepositoryInterface
{
    use FileManagerTrait;

    public function __construct(
        private readonly Artist $artist,
    ) {}

    public function getArtists()
    {
        return Artist::all();
    }

    public function getAllArtists()
    {
        return Artist::get();
    }

    public function getArtistById($id): Artist
    {
        return Artist::with('images')->where('id', $id)->first();
    }

    public function deleteArtist($id)
    {
        Artist::findOrFail($id)->delete();
    }

    public function updateArtist($id, array $data)
    {
        $artist = $this->getArtistById($id);
        $this->storeArtist($artist, $data, 'edit');
    }

    public function createArtist(array $data): Artist
    {
        $artist = new Artist();
        $this->storeArtist($artist, $data, 'create');
        return $artist;
    }


    private function storeArtist($artist, $data, $action)
    {
        DB::beginTransaction();

        try {
            $userData = [
                'name' => $data['name'],
            ];

            if ($action == 'create') {
                // Create user
                $user = User::create($userData);

                // Create artist
                $artistData = [
                    'name' => $data['name'],
                ];

                if (isset($data['image'])) {
                    $artistData['image'] = $this->upload('artist/', $data['image']->getClientOriginalExtension(), $data['image']);
                }

                $artist = Artist::create($artistData);
            } else {

                // Update artist
                $artist->update([
                    'name' => $data['name'],
                ]);
            }


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function bulkInsert(array $data): bool
    {
        $this->artist->insert($data);

        return true;
    }
}
