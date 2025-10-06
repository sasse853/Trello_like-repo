<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Boards;
use App\Services\NotificationService;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
{
    $this->app->singleton(NotificationService::class);
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.navbar', function ($view) {
            $user = Auth::user(); // Récupérer l'utilisateur connecté

            if ($user) {
                // Récupérer le dernier board de l'utilisateur connecté
                $board = Boards::where('user_id', $user->id)->latest()->first();
            } else {
                $board = null;
            }

            $view->with('board', $board);
        });
    }
}
