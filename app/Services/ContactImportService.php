<?php

namespace App\Services;

use App\Exceptions\ContactImportValidationException;
use App\Repositories\ContactRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class ContactImportService
{
    public function __construct(
        private readonly ContactRepository $contactRepo,
    ) {}
    private const CHUNK_SIZE = 1000;

    private const FIELD_NAMES = [
        0 => 'first_name',
        1 => 'last_name',
        2 => 'email',
        3 => 'date_of_birth',
        4 => 'organisation_record',
        5 => 'position',
        6 => 'organisation',
        7 => 'importance',
        8 => 'phone_number',
        9 => 'addional_phone_number',
        10 => 'website',
        11 => 'addional_website',
        12 => 'address',
        13 => 'gender',
        14 => 'spoken_languages',
        15 => 'date_of_birth',
        16 => 'max_budget',
        17 => 'consultant',
        18 => 'secondary_consultant',
        19 => 'contact_visibility',
        20 => 'mailing_settings',
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

                $this->contactRepo->bulkInsert($artists);
            });
    }

    private function validateRow(mixed $row, int $rowNumber): void
    {
        $validator = Validator::make([
            'first_name' => $row[0],
            'last_name' => $row[1],
            'email' => $row[2],
            'date_of_birth'=>$row[3],
        ], [
            'first_name' => 'required|min:3|unique:contacts',
            'last_name' => 'required|min:3|unique:contacts',
            'email' => 'nullable|email',
            'date_of_birth' => 'nullable|date|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            throw new ContactImportValidationException($rowNumber, $validator->errors()->all());
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