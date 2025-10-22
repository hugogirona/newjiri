<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProcessUploadContactAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public string $new_original_file_name)
    {
    }

    public function handle(): void
    {
        $sizes = config('avatars.sizes', []);
        $extension = config('avatars.jpg_image_type');
        $compression = config('avatars.jpg_compression');

        $originalDisk = config('avatars.original_disk', 'local');
        $variantDisk = config('avatars.variant_disk', 'public');

        $originalPath = rtrim(config('avatars.original_path'), '/')
            . '/' . $this->new_original_file_name;

        $binary = Storage::disk($originalDisk)->get($originalPath);

        foreach ($sizes as $size) {
            $w = $size['width'];
            $h = $size['height'];

            $sized = Image::read($binary)->cover($w, $h);

            // Formater le chemin
            $variantDir = sprintf(config('avatars.path_to_variant'), $w, $h);
            $fullVariantPath = rtrim($variantDir, '/') . '/' . $this->new_original_file_name;

            // IMPORTANT : Créer le dossier s'il n'existe pas
            $directory = dirname($fullVariantPath);

            if (!Storage::disk($variantDisk)->exists($directory)) {
                Storage::disk($variantDisk)->makeDirectory($directory, 0755, true);
            }

            // Sauvegarder l'image
            Storage::disk($variantDisk)->put(
                $fullVariantPath,
                $sized->encodeByExtension($extension, $compression)
            );

        }

    }
}
