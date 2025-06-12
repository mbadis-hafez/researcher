<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait FileManagerTrait
{
    /**
     * upload method working for image
     */
    protected function upload(string $dir, string $format, $image = null): string
    {
        if (! is_null($image)) {

            $imageName = Carbon::now()->toDateString().'-'.uniqid().'.'.$format;

            if (! Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir.$imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public function fileUpload(string $dir, string $format, $file = null): string
    {
        if (! is_null($file)) {
            $fileName = Carbon::now()->toDateString().'-'.uniqid().'.'.$format;
            if (! Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir.$fileName, file_get_contents($file));
        } else {
            $fileName = 'def.png';
        }

        return $fileName;
    }

    /**
     * @param  string  $fileType  image/file
     */
    public function update(string $dir, $oldImage, string $format, $image, string $fileType = 'image'): string
    {
        if (Storage::disk('public')->exists($dir.$oldImage)) {
            Storage::disk('public')->delete($dir.$oldImage);
        }
        return $fileType == 'file' ? $this->fileUpload($dir, $format, $image) : $this->upload($dir, $format, $image);
    }

    protected function delete(string $filePath): array
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return [
            'success' => 1,
            'message' => trans('Removed_successfully'),
        ];
    }
}