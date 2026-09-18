<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Jobs\GenerateTransparentProfileImage;
use App\Models\Traits\HasInfo;
use App\Models\Traits\HasSubscription;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasSubscription, HasInfo;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->email == 'tahir-asadov@outlook.com' || $this->email == 'asadovtahir@gmail.com';
        } else if ($panel->getId() == "customer") {
            return true;
        }
        return true;
    }

    public function isAdmin(): bool
    {
        return $this->email == 'tahir-asadov@outlook.com' || $this->email == 'asadovtahir@gmail.com';
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function socials()
    {
        return $this->hasMany(Social::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function hobbies()
    {
        return $this->hasMany(Hobby::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function freeTemplates()
    {
        return Template::active()->free()->get();
    }
    public static function availableTemplates()
    {
        return Template::active()->get()->filter(function ($template) {
            if (!$template->plan_id)
                return true;

            if (auth()->check()) {
                if (auth()->user()->hasSubscription()) {
                    dump($template->plan_id);
                    return $template->plan_id == auth()->user()->subscription->plan_id;
                }
            }
            return false;
        });
    }



    public static function availableResumes()
    {
        $templateIds = Template::availableTemplates()->pluck('id')->toArray();
        return auth()->user()->resumes()->whereIn('template_id', $templateIds)->latest()->get();
    }

    public function deleteAccount()
    {
        foreach ($this->resumes as $resume) {
            $resume->deleteFiles();
        }
        foreach ($this->resumes as $resume) {
            $resume->blocks()->delete();
        }
        $this->skills()->delete();
        $this->languages()->delete();
        $this->experiences()->delete();
        $this->educations()->delete();
        $this->socials()->delete();
        $this->projects()->delete();
        $this->awards()->delete();
        $this->certifications()->delete();
        $this->hobbies()->delete();
        $this->references()->delete();
        $this->resumes()->delete();
        $this->subscription()->delete();
        $this->orders()->delete();
        $this->cards()->delete();
        Transaction::where('user_id', $this->id)->delete();
        $this->profile()->delete();
        $this->delete();
    }

    public function addProfileImage($imageUrl = '')
    {
        if ($imageUrl) {
            // $imageUrl = 'aaa';
            // $imageUrl = 'https://media.licdn.com/dms/image/v2/D4E03AQFxbcpAYZw39Q/profile-displayphoto-shrink_100_100/profile-displayphoto-shrink_100_100/0/1718287854901?e=1756944000&v=beta&t=LeW_fXKaDFZBhR1cgCvBHj0eAonopjvWXhlXByo3BJQ';
            // $imageUrl = 'https://avatars.githubusercontent.com/u/1172253?v=4';
            // $imageUrl = 'https://lh3.googleusercontent.com/a/ACg8ocKrStBNU6NV2mBsDvu_MiozFRHc2LKKoWFW8p8_qOfb9Z1JZ-RJ=s96-c';
            $imageContent = file_get_contents($imageUrl);
            $dataUrl = 'data:image/jpeg;base64,' . base64_encode($imageContent);
            $fileName = (string) Str::uuid() . '.jpg';
            $filePath = storage_path('app/public/photos') . '/' . $fileName;
            $relativeFilePath = 'photos/' . $fileName;
            file_put_contents($filePath, $imageContent);
            if (is_file($filePath)) {
                $this->profile()->update(['profile_image' => $relativeFilePath]);
                GenerateTransparentProfileImage::dispatch($this->profile);
            }
        }
    }
}
