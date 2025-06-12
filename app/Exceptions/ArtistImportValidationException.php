<?php

namespace App\Exceptions;

use Exception;

class ArtistImportValidationException extends Exception
{
    public function __construct(int $rowNumber, array $errors)
    {
        $message = trans('Validation failed for row')." {$rowNumber}: ".implode(', ', $errors);
        parent::__construct($message);
    }
}