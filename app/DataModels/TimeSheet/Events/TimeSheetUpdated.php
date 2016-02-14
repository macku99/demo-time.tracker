<?php namespace App\DataModels\TimeSheet\Events;

use App\DataModels\TimeSheet\TimeSheet;
use Illuminate\Queue\SerializesModels;

/**
 * TimeSheetUpdated Event.
 *
 * @author  macku99
 * @version 1.0
 */
class TimeSheetUpdated
{

    use SerializesModels;

    /**
     * The timesheet being updated.
     *
     * @var TimeSheet
     */
    public $timeSheet;

    /**
     * @param TimeSheet $timeSheet
     */
    public function __construct($timeSheet)
    {
        $this->timeSheet = $timeSheet;
    }

}