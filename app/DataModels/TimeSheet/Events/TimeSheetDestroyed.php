<?php namespace App\DataModels\TimeSheet\Events;

/**
 * TimeSheetDestroyed Event.
 *
 * @author  macku99
 * @version 1.0
 */
class TimeSheetDestroyed
{

    /**
     * The timesheet id being destroyed.
     *
     * @var int
     */
    public $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

}