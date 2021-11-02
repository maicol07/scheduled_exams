<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Nette\Utils\Json;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::rememberForever(
            'translations',
            fn () => Json::encode(
                Json::decode(file_get_contents(resource_path('lang/'.app()->getLocale().'.json')))
            )
        );
    }
}
