<?php namespace App\Providers;

use App\DataModels\TimeSheet\TimeSheet;
use Carbon\Carbon;
use DB;
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
        DB::listen(function ($query) {
            //var_dump($query->sql);
            // $query->bindings
            // $query->time
        });

        TimeSheet::created(function (TimeSheet $timeSheet) {
            $userId = $timeSheet->user_id;
            $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
            $hours = $timeSheet->hours;

            $exists = DB::table('timesheets_summary')
                        ->where('user_id', $userId)
                        ->where('date', $timeSheetDate)
                        ->exists();

            if ($exists) {
                DB::table('timesheets_summary')
                  ->where('user_id', $userId)
                  ->where('date', $timeSheetDate)
                  ->update(['hours' => DB::raw("hours + {$hours}")]);
            } else {
                DB::table('timesheets_summary')
                  ->insert([
                      'user_id' => $userId,
                      'date'    => $timeSheetDate,
                      'hours'   => $hours,
                  ]);
            }
        });
        TimeSheet::updated(function (TimeSheet $timeSheet) {
            $userId = $timeSheet->user_id;
            $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
            $hours = $timeSheet->hours - $timeSheet->getOriginal('hours');

            DB::table('timesheets_summary')
              ->where('user_id', $userId)
              ->where('date', $timeSheetDate)
              ->update(['hours' => DB::raw("hours + {$hours}")]);
        });
        TimeSheet::deleted(function (TimeSheet $timeSheet) {
            $userId = $timeSheet->user_id;
            $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
            $hours = $timeSheet->hours;

            DB::table('timesheets_summary')
              ->where('user_id', $userId)
              ->where('date', $timeSheetDate)
              ->update(['hours' => DB::raw("hours - {$hours}")]);
        });
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