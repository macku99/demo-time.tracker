<?php namespace App\DataModels\TimeSheet;

/**
 * UserIsAllowedToWorkOnly24HoursPerDay Specification.
 *
 * @author  macku99
 * @version 1.0
 * @package Singularity
 */
class UserIsAllowedToWorkOnly24HoursPerDaySpecification
{

    /**
     * Check to see if the specification is satisfied
     *
     * @param  int    $timeSheetId
     * @param  int    $userId
     * @param  string $date
     * @param  int    $hours
     * @return bool
     */
    public function isSatisfiedBy($timeSheetId = null, $userId, $date, $hours)
    {
        $overWorked = TimeSheet::where('user_id', $userId)
                               ->where('date', $date)
                               ->where(function ($query) use ($timeSheetId) {
                                   if ( ! is_null($timeSheetId)) {
                                       $query->where('id', '<>', $timeSheetId);
                                   }
                               })
                               ->groupBy('date')
                               ->havingRaw("SUM(hours) + {$hours} > 24")
                               ->count();

        return $overWorked ? false : true;
    }

}