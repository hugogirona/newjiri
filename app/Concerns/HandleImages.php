<?php

namespace App\Concerns;

use App\Jobs\ProcessUploadContactAvatar;
use Illuminate\Support\Facades\Storage;

trait HandleImages
{
    public function generateAvatarImages($avatar): string
    {
        $unique_id = uniqid();
        $extension = config('avatars.jpg_image_type', 'jpg');

        $new_original_file_name = 'contact_' . $unique_id . '.' . $extension;

        $originalDisk = config('avatars.original_disk', 'local');
        $originalPath = config('avatars.original_path');

        // Sauvegarder l'original
        $full_path_to_original = Storage::disk($originalDisk)->putFileAs(
            $originalPath,
            $avatar,
            $new_original_file_name
        );

        if ($full_path_to_original) {
            // Dispatcher le job pour créer les variants
            ProcessUploadContactAvatar::dispatch($new_original_file_name);

            return $new_original_file_name;
        }

        return '';
    }

    public function deleteAvatarImages(string $filename): void
    {
        $originalDisk = config('avatars.original_disk', 'local');
        $variantDisk = config('avatars.variant_disk', 'public');
        $sizes = config('avatars.sizes', []);

        // Supprimer l'original
        $originalPath = rtrim(config('avatars.original_path'), '/') . '/' . $filename;
        if (Storage::disk($originalDisk)->exists($originalPath)) {
            Storage::disk($originalDisk)->delete($originalPath);
        }

        // Supprimer tous les variants
        foreach ($sizes as $size) {
            $w = $size['width'];
            $h = $size['height'];
            $variantDir = sprintf(config('avatars.path_to_variant'), $w, $h);
            $variantPath = rtrim($variantDir, '/') . '/' . $filename;

            if (Storage::disk($variantDisk)->exists($variantPath)) {
                Storage::disk($variantDisk)->delete($variantPath);
            }
        }
    }

}
