<?php namespace App\Repositories;

use App\DataModels\TimeSheetsSummary\TimeSheetsSummary;
use App\DataModels\TimeSheetsSummary\TimeSheetsSummaryRepository;

/**
 * TimeSheetsSummary Eloquent ORM Repository.
 *
 * @author  macku99
 * @version 1.0
 * @package Singularity
 */
class TimeSheetsSummaryEloquentORMRepository implements TimeSheetsSummaryRepository
{

    /**
     * Total hours worked for a certain date by a given user.
     *
     * @param  int    $userId
     * @param  string $date
     * @return int
     */
    public function totalHoursWorkedByUserForDate($userId, $date)
    {
        return TimeSheetsSummary::forUserAndDate($userId, $date)->value('hours');
    }

}