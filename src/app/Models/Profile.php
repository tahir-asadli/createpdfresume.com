<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\PngEncoder;

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function image_base64_url($filter)
    {

        $profileImage = $this->gender == 'female' ? 'female.jpg' : 'male.jpg';
        $image_path = $this->profile_image ? $this->image_path() : public_path('/images/' . $profileImage);
        $manager = new ImageManager(new Driver());
        $img = $manager->read($image_path);

        if ($filter == '') {
            return "data:image/png;base64," . base64_encode(file_get_contents($image_path));
        }

        if ($filter == 'effect-1') {
            $img->greyscale();
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-2') {
            $img->brightness(10);
            $img->colorize(0, 0, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-3') {
            $img->contrast(10);
            $img->colorize(0, 0, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }
        if ($filter == 'effect-4') {
            $img->brightness(10);
            $img->colorize(5, 0, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-5') {
            $img->brightness(10);
            $img->colorize(0, 5, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-6') {
            $img->brightness(10);
            $img->colorize(0, 0, 5);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-7') {
            $img->contrast(10);
            $img->colorize(5, 0, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-8') {
            $img->contrast(10);
            $img->colorize(0, 5, 0);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }

        if ($filter == 'effect-9') {
            $img->contrast(10);
            $img->colorize(0, 0, 5);
            return "data:image/png;base64," . base64_encode($img->encode(new PngEncoder()));
        }


        return "data:image/png;base64," . base64_encode(file_get_contents($this->image_path()));
    }

    public function image_url()
    {
        if ($this->transparent) {
            if (is_file(storage_path('app/public/' . $this->transparent_profile_image))) {
                return '/storage/' . $this->transparent_profile_image;
            }
        }
        return '/storage/' . $this->profile_image;
    }

    public function image_path()
    {

        if ($this->transparent) {
            if (is_file(storage_path('app/public/' . $this->transparent_profile_image))) {
                return storage_path('app/public/' . $this->transparent_profile_image);
            }
        }
        if (is_file(storage_path('app/public/' . $this->profile_image))) {
            return storage_path('app/public/' . $this->profile_image);
        }
        return null;
    }
}
