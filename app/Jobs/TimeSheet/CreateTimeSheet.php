<?php namespace App\Jobs\TimeSheet;

use App\DataModels\TimeSheet\Events\TimeSheetCreated;
use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\TimeSheet\UserIsAllowedToWorkOnly24HoursPerDaySpecification;
use App\Jobs\Job;
use Carbon\Carbon;

/**
 * CreateTimeSheet Job.
 *
 * @author  macku99
 * @version 1.0
 */
class CreateTimeSheet extends Job
{

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
     * @param int    $userId
     * @param string $date
     * @param int    $hours
     * @param string $description
     */
    public function __construct($userId, $date, $hours, $description)
    {
        $this->userId = $userId;
        $this->date = (new Carbon($date))->toDateString();
        $this->hours = $hours;
        $this->description = $description;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Assert\that((new UserIsAllowedToWorkOnly24HoursPerDaySpecification)->isSatisfiedBy(
            null, $this->userId, $this->date, $this->hours
        ))->true();

        $timeSheet = new TimeSheet();

        $timeSheet->user_id = $this->userId;
        $timeSheet->date = $this->date;
        $timeSheet->hours = $this->hours;
        $timeSheet->description = $this->description;

        $timeSheet->save();

        event(new TimeSheetCreated($timeSheet));
    }

}