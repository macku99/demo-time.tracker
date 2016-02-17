<?php namespace App\Providers;

use App\DataModels\TimeSheet\TimeSheet;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*TimeSheet::created(function (TimeSheet $timeSheet) {
            \DB::statement('insert into timesheets_summary (user_id, date, hours) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE hours = hours + ?',
                [
                    $timeSheet->user_id,
                    (new Carbon($timeSheet->date))->toDateString(),
                    $timeSheet->hours,
                    $timeSheet->hours,
                ]);
        });*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}