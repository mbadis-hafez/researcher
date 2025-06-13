<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artwork;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;

class ArtworkSeeder extends Seeder
{
    public function run()
    {
        // Path to your CSV file
        $csvFile = database_path('seeders/data/artwork.csv');

        // Check if file exists
        if (!File::exists($csvFile)) {
            $this->command->error("The CSV file does not exist at path: {$csvFile}");
            return;
        }

        // Read CSV file
        $csvData = array_map('str_getcsv', file($csvFile));

        // Remove header row if present
        array_shift($csvData);

        foreach ($csvData as $row) {
            // Skip empty rows
            if (empty($row)) {
                continue;
            }
            // Map CSV fields to database columns
            $artworkData = [
                'id' => $row[0] ?? null,
                'title' => isset($row[1]) && $row[1] !== 'N/A' ? $row[1] : null,
                'year' => isset($row[2]) && $row[2] !== '0000' ? $row[2] : null,
                'medium' => isset($row[3]) && $row[3] !== 'N/A' ? $row[3] : null,
                'dimensions' => isset($row[4]) && $row[4] !== 'N/A' ? $row[4] : null,
                'description' => isset($row[5]) && $row[5] !== 'N/A' ? $row[5] : null,
                'additional_info' => isset($row[6]) && $row[6] !== 'N/A' ? $row[6] : null,
                'provenance' => isset($row[7]) && $row[7] !== 'N/A' ? $row[7] : null,
                'exhibition' => isset($row[8]) && ($row[8] !== 'N/A' || $row[8] == '') ? $row[8] : null,
                'image_path' => isset($row[9]) ? json_encode([$row[9] !== 'N/A' ? $row[9] : null]) : null,
                'current_location' => isset($row[10]) && $row[10] !== 'N/A' ? $row[10] : null,
                'created_at' => isset($row[11]) && !empty($row[11]) ? Carbon::parse($row[11]) : now(),
                'updated_at' => isset($row[12]) && !empty($row[12]) ? Carbon::parse($row[12]) : now(),
                'artist_id'=>$row[19],
                'author_id' => 15, // Hardcoded as requested
                'source' => isset($row[16]) && $row[16] !== 'N/A' ? $row[16] : null,
                'researcher_id' => 15, // Hardcoded as requested
                'status' => 4, // Hardcoded as requested
            ];

            // Create or update the artwork
            Artwork::updateOrCreate(['id' => $artworkData['id']], $artworkData);
        }

        $this->command->info('Artworks seeded successfully!');
    }
}
