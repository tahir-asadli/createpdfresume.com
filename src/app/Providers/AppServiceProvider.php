<?php
namespace App\Providers;

use App\Models\Customer;
use App\Models\Resume;
use App\Models\Template;
use App\Models\User;
use App\Observers\CustomerObserver;
use App\Observers\ResumeObserver;
use App\Observers\TemplateObserver;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Opcodes\LogViewer\Facades\LogViewer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        Template::observe(TemplateObserver::class);
        Resume::observe(ResumeObserver::class);
        User::observe(UserObserver::class);
        Customer::observe(CustomerObserver::class);
        if (App::environment('production')) {
            URL::forceScheme('https');
        }
        LogViewer::auth(function ($request) {
            return $request->user() && in_array($request->user()->email, ['asadovtahir@gmail.com', 'tahir-asadov@outlook.com',]);
        });
        TrimStrings::skipWhen(function (Request $request) {
            $post = $request->all();
            $formFields = !empty($post['form']) ? $post['form'] : [];
            return $request->path() == 'dashboard/update-block-settings' &&
                in_array('title', array_keys($formFields));
        });
    }
}
