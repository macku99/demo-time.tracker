<?php namespace App\Jobs\TimeSheet;

use App\DataModels\TimeSheet\Events\TimeSheetUpdated;
use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\TimeSheet\UserIsAllowedToWorkOnly24HoursPerDaySpecification;
use App\Jobs\Job;

/**
 * UpdateTimeSheet Job.
 *
 * @author  macku99
 * @version 1.0
 */
class UpdateTimeSheet extends Job
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var int
     */
    protected $hours;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param int    $id
     * @param int    $userId
     * @param string $date
     * @param int    $hours
     * @param string $description
     */
    public function __construct($id, $userId, $date, $hours, $description)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->date = $date;
        $this->hours = $hours;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Assert\that((new UserIsAllowedToWorkOnly24HoursPerDaySpecification)->isSatisfiedBy(
            $this->id, $this->userId, $this->date, $this->hours
        ))->true();

        $timeSheet = TimeSheet::findOrFail($this->id);

        $timeSheet->date = $this->date;
        $timeSheet->hours = $this->hours;
        $timeSheet->description = $this->description;

        $timeSheet->save();

        event(new TimeSheetUpdated($timeSheet));
    }

}