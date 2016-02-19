<?php namespace App\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function (View $view) {
            $loggedInUser = request()->user();
            $view->with('userIsLoggedIn', ! is_null($loggedInUser));
            $view->with('userIsGuest', is_null($loggedInUser));
            $view->with('loggedInUserId', (! is_null($loggedInUser) ? $loggedInUser->id : null));
            $view->with('loggedInUserName', (! is_null($loggedInUser) ? $loggedInUser->name : null));
            $view->with('loggedInUserPreferredDailyHours',
                (! is_null($loggedInUser) ? $loggedInUser->preferred_daily_hours : null)
            );
            $view->with('loggedInUserIsAdmin', (! is_null($loggedInUser) ? $loggedInUser->isAdmin() : null));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}