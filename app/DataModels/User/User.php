<?php namespace App\DataModels\User;

use App\DataModels\TimeSheet\TimeSheet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User Model.
 *
 * @author  macku99
 * @version 1.0
 *
 * @property string     $id
 * @property string     $name
 * @property string     $email
 * @property string     $password
 * @property int        $preferred_daily_hours
 * @property Collection $timesheets
 * @property Carbon     $created_at
 * @property Carbon     $updated_at
 */
class User extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timesheets()
    {
        return $this->hasMany(TimeSheet::class);
    }

}