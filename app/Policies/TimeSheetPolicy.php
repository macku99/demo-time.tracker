<?php namespace App\Policies;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeSheetPolicy
{

    use HandlesAuthorization;


    /**
     * Determine if the given user can view timesheets list.
     *
     * @param  User   $user
     * @param  string $timeSheetClass
     * @param  int    $ownerId
     * @return bool
     */
    public function index(User $user, $timeSheetClass, $ownerId)
    {
        return $user->id == $ownerId;
    }

    /**
     * Determine if the given user can view a timesheet details.
     *
     * @param  User   $user
     * @param  string $timeSheetClass
     * @param  int    $ownerId
     * @return bool
     */
    public function show(User $user, $timeSheetClass, $ownerId)
    {
        return $user->id == $ownerId;
    }

    /**
     * Determine if the given user can create a new timesheet.
     *
     * @param  User   $user
     * @param  string $timeSheetClass
     * @param  int    $ownerId
     * @return bool
     */
    public function store(User $user, $timeSheetClass, $ownerId)
    {
        return $user->id == $ownerId;
    }

    /**
     * Determine if the given user can update a timesheet.
     *
     * @param  User      $user
     * @param  TimeSheet $timeSheet
     * @return bool
     */
    public function update(User $user, TimeSheet $timeSheet)
    {
        return $user->id == $timeSheet->user_id;
    }

    /**
     * Determine if the given user can delete a timesheet.
     *
     * @param  User      $user
     * @param  TimeSheet $timeSheet
     * @return bool
     */
    public function destroy(User $user, TimeSheet $timeSheet)
    {
        return $user->id == $timeSheet->user_id;
    }

    /**
     * The admin users have all abilities.
     *
     * @param  User   $user
     * @param  string $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

}