<?php namespace App\Providers;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\TimeSheetsSummary\TimeSheetsSummary;
use App\DataModels\TimeSheetsSummary\TimeSheetsSummaryRepository;
use App\Repositories\TimeSheetsSummaryCachingRepository;
use App\Repositories\TimeSheetsSummaryEloquentORMRepository;
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
        TimeSheet::created(function (TimeSheet $timeSheet) {
            (new TimeSheetsSummary)->whenTimeSheetIsCreated($timeSheet);
        });
        TimeSheet::updated(function (TimeSheet $timeSheet) {
            (new TimeSheetsSummary)->whenTimeSheetIsUpdated($timeSheet);
        });
        TimeSheet::deleted(function (TimeSheet $timeSheet) {
            (new TimeSheetsSummary)->whenTimeSheetIsDeleted($timeSheet);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TimeSheetsSummaryRepository::class, function () {
            return new TimeSheetsSummaryCachingRepository(
                $this->app->make(TimeSheetsSummaryEloquentORMRepository::class),
                $this->app->make('cache.store'));
        });
    }

}