<?php namespace App;

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
    protected $dates = ['date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}