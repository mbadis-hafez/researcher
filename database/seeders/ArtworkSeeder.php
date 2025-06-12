<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Artist;
use App\Models\ArtWork;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArtworkSeeder extends Seeder
{
    public function run()
    {
        // Path to your CSV file (store it in storage/app/imports/artworks.csv)
        $csvFile = database_path('seeders/data/artwork.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            $this->command->info("Please create the file or check the path");
            return;
        }

        // Create categories first
        $categories = $this->initializeCategories();

        // Open the CSV file
        $file = fopen($csvFile, 'r');
        
        // Read header row
        $header = fgetcsv($file);
        
        $statusMap = [
            'Sold' => 3,
            'Availble / For Sale' => 2,
            'Not available' => 1,
            'Not for sale' => 4,
            'Details Pending' => 5
        ];

        $count = 0;
        
        // Process each row
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);
            
            try {
                $artist = $this->findOrCreateArtist($data);
                $category = $this->determineCategory($data['Medium'], $categories);
                $price = $this->parsePrice($data['Price in Atrgalleria']);
                $status = $statusMap[$data['Status in Art Galleria']] ?? 1;
                
                ArtWork::create([
                    'artist_id' => $artist->id,
                    'category_id' => $category->id,
                    'title' => $data['Artist Title'],
                    'description' => $data['Comment'] ?? 'No description',
                    'dimensions' => $data['Dimensions of artwork without frame'],
                    'medium' => $data['Medium'],
                    'year' => $data['Year'],
                    'condition_report' => $data['Condition report'],
                    'current_location' => $data['Current Location'],
                    'price' => $price,
                    'status' => $status,
                    'is_active' => true,
                    'image_path' => $this->downloadAndStoreImage($data['Link of photo']),
                    'provenance' => $data['Provenance'] ?? '',
                    'inventory_number' => $data['Inventory N. in Art Galleria'] ?? null,
                    'signature' => $data['Signture'] ?? null,
                    'edition' => $data['Edition'] ?? null,
                ]);
                
                $count++;
            } catch (\Exception $e) {
                $this->command->error("Error importing row: " . $e->getMessage());
            }
        }
        
        fclose($file);
        
        $this->command->info("Successfully imported {$count} artworks");
    }

    private function initializeCategories()
    {
        $categories = [
            'ceramic tiles' => 'Ceramics & Glass',
            'gouache on wood' => 'Painting',
            'natural pigments and gouache on paper' => 'Drawing, Collage Or Other Work On Paper',
            'acrylic and gouache on wooden planks' => 'Painting'
        ];

        $result = [];
        foreach ($categories as $medium => $categoryName) {
            $result[$medium] = Category::firstOrCreate([
                'name' => $categoryName,
            ]);
        }

        return $result;
    }

    private function findOrCreateArtist($data)
    {
        $artistName = trim($data['Artist Name']);
        $artist = Artist::where('name', $artistName)->first();

        if (!$artist) {
            $username = Str::slug($artistName);
            $email = null;
            
            $user = User::create([
                'name' => $artistName,
                'email' => $email,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $artist = Artist::create([
                'user_id' => $user->id,
                'name' => $artistName,
                'country' => $data['The Nationality'],
            ]);
        }

        return $artist;
    }

    private function determineCategory($medium, $categories)
    {
        $medium = strtolower($medium);
        
        foreach ($categories as $key => $category) {
            if (str_contains($medium, $key)) {
                return $category;
            }
        }
        
        return Category::firstOrCreate([
            'name' => 'Unclassified',
        ]);
    }

    private function parsePrice($price)
    {
        return (float) preg_replace('/[^0-9.]/', '', $price);
    }

    private function downloadAndStoreImage($url)
    {
        try {
            if (empty($url)) return null;
            
            $contents = @file_get_contents($url);
            if ($contents === false) return null;
            
            $name = basename(parse_url($url, PHP_URL_PATH));
            $path = 'artworks/' . uniqid() . '_' . $name;
            
            Storage::disk('public')->put($path, $contents);
            
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}