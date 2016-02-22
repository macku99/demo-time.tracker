<?php namespace App\DataModels\TimeSheet;

use App\DataModels\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * TimeSheet Model.
 *
 * @author  macku99
 * @version 1.0
 *
 * @property string $id
 * @property int    $user_id
 * @property Carbon $date
 * @property int    $hours
 * @property string $description
 * @property User   $user
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TimeSheet extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'timesheets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'hours', 'description',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  string $value
     * @return string
     */
    public function getDateAttribute($value)
    {
        return (new Carbon($value))->format('F jS Y');
    }

    /**
     * @param  string $value
     * @return string
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = (new Carbon($value))->toDateString();
    }

    /**
     * Scope a query to only include rows for given user id and date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $rangeStartDate
     * @param  string                                $rangeEndDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereDatesInRange($query, $rangeStartDate = null, $rangeEndDate = null)
    {
        return $query->where(function ($query) use ($rangeStartDate, $rangeEndDate) {
            if (!is_null($rangeStartDate)) {
                $query->where('date', '>=', $rangeStartDate);
            }
            if (!is_null($rangeEndDate)) {
                $query->where('date', '<=', $rangeEndDate);
            }
        });
    }

}