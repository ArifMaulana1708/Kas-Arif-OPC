<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
    // public function boot(): void
    // {
    //     Paginator::useBootstrapFive();

    //     // Aplikasi diakses via subfolder (http://localhost/kas_arif/public),
    //     // sedangkan Livewire memakai URL absolut dari domain root (/livewire/...).
    //     // Tanpa ini, request update Livewire nyasar ke http://localhost/livewire/...
    //     // dan Apache menjawab 404 (filter Uang Masuk/Keluar & dashboard mati).
    //     $base = trim((string) parse_url((string) config('app.url'), PHP_URL_PATH), '/');
    //     if ($base !== '') {
    //         Livewire::setUpdateRoute(
    //             fn ($handle) => Route::post('/' . $base . '/livewire/update', $handle)->middleware('web')
    //         );
    //     }
    // }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
