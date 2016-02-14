<?php namespace App\Jobs\TimeSheet;

use App\DataModels\TimeSheet\Events\TimeSheetDestroyed;
use App\DataModels\TimeSheet\TimeSheet;
use App\Jobs\Job;

/**
 * DestroyTimeSheet Job.
 *
 * @author  macku99
 * @version 1.0
 */
class DestroyTimeSheet extends Job
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $timeSheet = TimeSheet::findOrFail($this->id);
        $timeSheet->delete();

        event(new TimeSheetDestroyed($this->id));
    }

}