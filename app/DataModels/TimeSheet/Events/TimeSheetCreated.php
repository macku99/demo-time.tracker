<?php namespace App\DataModels\TimeSheet\Events;

use App\DataModels\TimeSheet\TimeSheet;
use Illuminate\Queue\SerializesModels;

/**
 * TimeSheetCreated Event.
 *
 * @author  macku99
 * @version 1.0
 */
class TimeSheetCreated
{

    use SerializesModels;

    /**
     * The timesheet being created.
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