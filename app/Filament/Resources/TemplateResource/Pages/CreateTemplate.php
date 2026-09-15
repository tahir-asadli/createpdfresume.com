<?php

namespace App\Filament\Resources\TemplateResource\Pages;

use App\Filament\Resources\TemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CreateTemplate extends CreateRecord
{
    protected static string $resource = TemplateResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['image'])) {
            $image = $data['image'];
            $original_file_path = Storage::disk('template')->path($image);
            $original_file_pathinfo = pathinfo($original_file_path);
            $manager = new ImageManager(new Driver());
            $new_file_name = $original_file_pathinfo['filename'] . '.webp';
            $new_thumb_file_name = $original_file_pathinfo['filename'] . '.thumb.webp';
            $new_file_path = $original_file_pathinfo['dirname'] . '/' . $new_file_name;
            $new_thumb_file_path = $original_file_pathinfo['dirname'] . '/' . $new_thumb_file_name;
            $image = $manager->read($original_file_path);
            $thumb = $manager->read($original_file_path);
            $image->toWebp()->save($new_file_path);
            $thumb->scale(width: 400);
            $thumb->toWebp()->save($new_thumb_file_path);
            $data['image'] = $new_file_name;
            // unlink($original_file_path);
        }
        return $data;
    }
}
