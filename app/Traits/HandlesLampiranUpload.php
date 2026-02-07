<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

trait HandlesLampiranUpload
{
    /**
     * Ambil daftar file terunggah dan pastikan berbentuk array.
     *
     * @return array<int, UploadedFile>
     */
    protected function prepareUploadedFiles(Request $request, string $field): array
    {
        $files = $request->file($field);

        if (!$files) {
            return [];
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        return array_values(array_filter($files, fn ($file) => $file instanceof UploadedFile));
    }

    /**
     * Pastikan total ukuran file tidak lebih dari 5MB.
     *
     * @param  array<int, UploadedFile>  $files
     */
    protected function ensureTotalAttachmentsSize(array $files, string $field): void
    {
        if (empty($files)) {
            return;
        }

        $totalBytes = array_reduce(
            $files,
            fn ($carry, UploadedFile $file) => $carry + $file->getSize(),
            0
        );

        if ($totalBytes > 5 * 1024 * 1024) {
            throw ValidationException::withMessages([
                $field => 'Total ukuran lampiran maksimal 5MB.',
            ]);
        }
    }

    /**
     * Simpan file ke storage publik dan kembalikan path-nya.
     *
     * @param  array<int, UploadedFile>  $files
     * @return array<int, string>
     */
    protected function storeAttachments(array $files, string $directory): array
    {
        if (empty($files)) {
            return [];
        }

        return array_values(array_map(
            fn (UploadedFile $file) => $file->store($directory, 'public'),
            $files
        ));
    }
}


