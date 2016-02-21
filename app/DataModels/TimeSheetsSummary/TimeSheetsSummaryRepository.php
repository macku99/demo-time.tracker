<?php namespace App\DataModels\TimeSheetsSummary;

/**
 * TimeSheetsSummary Repository.
 *
 * @author  macku99
 * @version 1.0
 */
interface TimeSheetsSummaryRepository
{

    /**
     * Total hours worked for a certain date by a given user.
     *
     * @param  int    $userId
     * @param  string $date
     * @return int
     */
    public function totalHoursWorkedByUserForDate($userId, $date);

}