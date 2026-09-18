<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class SocialController extends Controller
{
    public function google()
    {
        return Socialite::driver('google')->redirect();
    }
    public function google_callback()
    {

        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();
        if ($user) {
            $user->update([
                'google_id' => $googleUser->id,
            ]);
        } else {
            $user = User::make();
            info('$googleUser', [$googleUser]);
            $user->google_id = $googleUser->id;
            $user->name = $googleUser->name;
            $user->email = $googleUser->email;
            $user->email_verified_at = now();
            $user->password = Hash::make(Str::password());
            $user->saveQuietly();
            if (!$user->hasSubscription()) {
                $user->initializeAccount();
                info('initializeAccount');
                $user->addProfileImage($googleUser->avatar);
            }
        }

        Auth::login($user);
        return redirect('/dashboard');
    }
    public function github()
    {
        return Socialite::driver('github')->redirect();
    }
    public function github_callback()
    {

        $githubUser = Socialite::driver('github')->stateless()->user();
        $user = User::where('email', $githubUser->email)->first();
        info('$githubUser', [$githubUser]);
        if ($user) {
            $user->update([
                'github_id' => $githubUser->id,
            ]);
        } else {
            $user = User::make();
            $name = $githubUser->email;
            if ($githubUser->nickname != null) {
                $name = $githubUser->nickname;
            }
            if ($githubUser->name != null) {
                $name = $githubUser->name;
            }
            $user->github_id = $githubUser->id;
            $user->name = $name;
            $user->email = $githubUser->email;
            $user->email_verified_at = now();
            $user->password = Hash::make(Str::password());
            $user->saveQuietly();
            if (!$user->hasSubscription()) {
                $user->initializeAccount();
                info('initializeAccount githubUser');
                $user->addProfileImage($githubUser->avatar);
            }
        }

        Auth::login($user);
        return redirect('/dashboard');
    }
    public function linkedin()
    {
        return Socialite::driver('linkedin-openid')->redirect();
    }
    public function linkedin_callback()
    {

        $linkedinUser = Socialite::driver('linkedin-openid')->scopes(['r_liteprofile', 'r_emailaddress'])->stateless()->user();
        $user = User::where('email', $linkedinUser->email)->first();
        info('$linkedinUser', [$linkedinUser]);
        if ($user) {
            $user->update([
                'linkedin' => $linkedinUser->id,
            ]);
        } else {
            $user = User::make();
            $user->linkedin = $linkedinUser->id;
            $user->name = $linkedinUser->name;
            $user->email = $linkedinUser->email;
            $user->email_verified_at = now();
            $user->password = Hash::make(Str::password());
            $user->saveQuietly();
            if (!$user->hasSubscription()) {
                $user->initializeAccount();
                $user->addProfileImage($linkedinUser->avatar);
            }
        }

        Auth::login($user);
        return redirect('/dashboard');
    }
}
