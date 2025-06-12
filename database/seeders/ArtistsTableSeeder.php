<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artist;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ArtistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to your CSV file
        $csvPath = database_path('seeders/data/artists.csv');
        
        // Check if file exists
        if (!File::exists($csvPath)) {
            $this->command->error("CSV file not found: {$csvPath}");
            return;
        }

        // Read CSV file
        $csvData = array_map('str_getcsv', file($csvPath), array_fill(0, count(file($csvPath)), ';'));

        // Remove header row
        array_shift($csvData);

        foreach ($csvData as $row) {
            // Skip invalid rows
            if (count($row) < 6) continue;

            // Combine first and last name for the 'name' field
            $fullName = trim($row[0]) . ' ' . trim($row[1]);
            
            // Generate email if not provided in CSV
            $email = !empty($row[4]) ? $row[4] : Str::slug($fullName) . '@example.com';

            Artist::create([
                'name' => $fullName,
                'email' => $email,
                'password' => Hash::make('password'), // Default password
                'description' => $this->cleanText($row[2]), // Using artist_statement as description
                'short_biography' => $this->cleanText($row[2]), // Using first part of statement
                'full_biography' => $this->cleanText($row[2]), // Full artist statement
                'website' => 'https://' . Str::slug($fullName) . '.com', // Generated website
                'notes' => 'Imported from CSV on ' . now()->format('Y-m-d'),
                'country' => $row[3], // Region becomes country
                'image' => $this->getArtistImage($fullName), // Custom image function
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Successfully seeded artists data!');
    }

    /**
     * Clean text from CSV formatting issues
     */
    private function cleanText(string $text): string
    {
        // Remove CSV escape characters
        $text = str_replace('""', '"', $text);
        $text = trim($text, '"');
        
        // Fix encoding issues
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        
        return $text;
    }

    /**
     * Generate appropriate image URL based on artist name
     */
    private function getArtistImage(string $name): string
    {
        $slug = Str::slug($name);
        return "https://source.unsplash.com/random/640x480/?portrait,artist,{$slug}";
    }
}