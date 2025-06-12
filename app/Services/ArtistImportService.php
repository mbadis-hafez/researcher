<?php

namespace App\Services;

use App\Exceptions\ArtistImportValidationException;
use App\Repositories\ArtistRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class ArtistImportService
{
    public function __construct(
        private readonly ArtistRepository $artistRepo,
    ) {}
    private const CHUNK_SIZE = 1000;

    private const FIELD_NAMES = [
        0 => 'name',
        1 => 'email',
        2 => 'password',
        3 => 'artist_date',
        4 => 'description',
        5 => 'short_biography',
        6 => 'full_biography',
        7 => 'website',
        8 => 'notes',
        9 => 'country',
    ];

    public function import(UploadedFile $file): void
    {
        $rows = $this->getExcelRows($file);
        $this->processRows($rows);
    }

    private function getExcelRows(UploadedFile $file): Collection
    {
        return Excel::toCollection(null, $file)
            ->first()
            ->slice(1); // Skip header row
    }

    private function processRows(Collection $rows): void
    {
        $rows->chunk(self::CHUNK_SIZE)
            ->each(function ($chunk, $chunkIndex) {
                $artists = $chunk->map(function ($row, $index) use ($chunkIndex) {
                    $rowNumber = ($chunkIndex * self::CHUNK_SIZE) + $index + 1;
                    $this->validateRow($row->toArray(), $rowNumber);

                    return $this->mapRowToDevice($row);
                })->toArray();

                $this->artistRepo->bulkInsert($artists);
            });
    }

    private function validateRow(mixed $row, int $rowNumber): void
    {
        $validator = Validator::make([
            'name' => $row[0],
            'email' => $row[1],
            'artist_date'=>$row[3],
            'country' => $row[9],
        ], [
            'name' => 'required|min:3|unique:artists',
            'email' => 'nullable|email',
            'artist_date' => 'nullable|date|date_format:Y-m-d',
            'country' => ['nullable','string',
            'size:2', // Ensure exactly 2 characters
            'uppercase', // Ensure it is uppercase
            Rule::exists('countries', 'iso2')] // Ensure it exists in the countries table'],
        ]);

        if ($validator->fails()) {
            throw new ArtistImportValidationException($rowNumber, $validator->errors()->all());
        }
    }

    private function mapRowToDevice(mixed $row): array
    {
        return array_merge(
            collect(self::FIELD_NAMES)
                ->mapWithKeys(fn ($field, $index) => [$field => $row[$index] ?? null])
                ->toArray(),
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}