<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Alamat resource route berbahasa Indonesia
        Route::resourceVerbs([
            'create' => 'tambah',
            'edit'   => 'ubah',
        ]);
    }
}
