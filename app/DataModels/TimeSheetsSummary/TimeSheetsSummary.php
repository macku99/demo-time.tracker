<?php namespace App\DataModels\TimeSheetsSummary;

use App\DataModels\TimeSheet\TimeSheet;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * TimeSheetsSummary Model.
 *
 * @author  macku99
 * @version 1.0
 *
 * @property string $id
 * @property int    $user_id
 * @property Carbon $date
 * @property int    $hours
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TimeSheetsSummary extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'timesheets_summary';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'date', 'hours',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Scope a query to only include rows for given user id and date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $timeSheetUserId
     * @param  string                                $timeSheetDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUserAndDate($query, $timeSheetUserId, $timeSheetDate)
    {
        return $query->where('user_id', $timeSheetUserId)
                     ->where('date', $timeSheetDate);
    }

    /**
     * @param TimeSheet $timeSheet
     */
    public function whenTimeSheetIsCreated(TimeSheet $timeSheet)
    {
        $timeSheetUserId = $timeSheet->user_id;
        $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
        $hours = $timeSheet->hours;

        $exists = static::forUserAndDate($timeSheetUserId, $timeSheetDate)
                        ->count();
        if ($exists) {
            static::forUserAndDate($timeSheetUserId, $timeSheetDate)
                  ->update(['hours' => DB::raw("hours + {$hours}")]);
        } else {
            (new static)->create([
                'user_id' => $timeSheetUserId,
                'date'    => $timeSheetDate,
                'hours'   => $hours,
            ]);
        }

        app('cache.store')->forget("timesheets.summary.{$timeSheetUserId}.{$timeSheetDate}");
    }

    /**
     * @param TimeSheet $timeSheet
     */
    public static function whenTimeSheetIsUpdated(TimeSheet $timeSheet)
    {
        $timeSheetUserId = $timeSheet->user_id;
        $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
        $hours = $timeSheet->hours - $timeSheet->getOriginal('hours');

        static::forUserAndDate($timeSheetUserId, $timeSheetDate)
              ->update(['hours' => DB::raw("hours + {$hours}")]);

        app('cache.store')->forget("timesheets.summary.{$timeSheetUserId}.{$timeSheetDate}");
    }

    /**
     * @param TimeSheet $timeSheet
     */
    public static function whenTimeSheetIsDeleted(TimeSheet $timeSheet)
    {
        $timeSheetUserId = $timeSheet->user_id;
        $timeSheetDate = (new Carbon($timeSheet->date))->toDateString();
        $hours = $timeSheet->hours;

        static::forUserAndDate($timeSheetUserId, $timeSheetDate)
              ->update(['hours' => DB::raw("hours - {$hours}")]);

        app('cache.store')->forget("timesheets.summary.{$timeSheetUserId}.{$timeSheetDate}");
    }

}