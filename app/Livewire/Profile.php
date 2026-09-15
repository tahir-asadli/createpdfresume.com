<?php

namespace App\Livewire;

use App\Jobs\GenerateTransparentProfileImage;
use App\Livewire\Forms\EditProfileForm;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class Profile extends ModalComponent
{


    use WithFileUploads;
    // public EditProfileForm $form;
    public $profile = null;


    #[Validate('nullable|max:64', 'Fullname')]
    public $fullname = '';

    #[Validate('nullable|max:64', 'Job title')]
    public $job_title = '';

    #[Validate('nullable|max:512', 'Slogan')]
    public $slogan = '';

    #[Validate('nullable|max:2000', 'About me')]
    public $about = '';

    #[Validate('max:3072|mimes:jpg,jpeg,png|image', 'Profile image')]
    public $profile_image = '';

    #[Validate('nullable|max:64', 'Phone')]
    public $phone = '';

    #[Validate('nullable|email|max:64', 'E-mail')]
    public $email = '';

    #[Validate('nullable|max:64', 'Website')]
    public $web = '';

    #[Validate('nullable|max:128', 'Address')]
    public $address = '';

    #[Validate('nullable|in:male,female', 'Gender')]
    public $gender = '';

    #[Validate('required|in:en,az', 'Language')]
    public $language = 'en';

    protected function messages()
    {
        return [
            'profile_image.max' => __('The image size can be a maximum of 3MB'),
        ];
    }

    public function upsert()
    {
        $photo = $this->profile_image ? $this->profile_image->storePublicly('photos', 'public') : null;
        $profile = auth()->user()->profile()->first();
        $data = [
            'fullname' => cl($this->fullname),
            'job_title' => cl($this->job_title),
            'slogan' => cl($this->slogan),
            'profile_image' => cl($this->profile_image),
            'phone' => cl($this->phone),
            'about' => cl($this->about),
            'email' => cl($this->email),
            'web' => cl($this->web),
            'address' => cl($this->address),
            'gender' => cl($this->gender),
        ];
        if ($photo) {
            $data['profile_image'] = $photo;
            if ($profile && $profile->profile_image) {
                $oldImage = storage_path('app/public/photos/' . $profile->profile_image);
                if (is_file($oldImage)) {
                    unlink($oldImage);
                }
            }
        } else {
            unset($data['profile_image']);
        }
        if ($profile) {
            $profile->update($data);
            GenerateTransparentProfileImage::dispatch($profile);
        } else {
            $profile = Profile::create($data + ['user_id' => auth()->user()->id]);
            GenerateTransparentProfileImage::dispatch($profile);
        }

    }
    public function mount()
    {
        $this->profile = auth()->user()->profile()->first();
        if ($this->profile) {
            $this->fullname = $this->profile->fullname;
            $this->job_title = $this->profile->job_title;
            $this->slogan = $this->profile->slogan;
            $this->about = $this->profile->about;
            $this->phone = $this->profile->phone;
            $this->email = $this->profile->email;
            $this->web = $this->profile->web;
            $this->address = $this->profile->address;
            $this->gender = $this->profile->gender;
        }
    }

    #[On('refresh')]
    public function render()
    {
        return view('livewire.profile.index', [
            'profile' => $this->profile
        ]);
    }

    public function submit()
    {
        $attributes = [
            'fullname' => __('Fullname'),
            'slogan' => __('Slogan'),
            'job_title' => __('Job title'),
            'profile_image' => __('Profile image'),
            'phone' => __('Phone'),
            'email' => __('E-mail'),
            'web' => __('Website'),
            'address' => __('Address'),
            'gender' => __('Gender'),
            'about' => __('About me'),
        ];
        $this->validate(attributes: $attributes);
        $this->upsert();
        $this->dispatch('refresh');
    }
}
