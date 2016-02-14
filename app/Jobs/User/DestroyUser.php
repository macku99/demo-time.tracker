<?php namespace App\Jobs\User;

use App\DataModels\User\Events\UserDestroyed;
use App\DataModels\User\User;
use App\Jobs\Job;

/**
 * DestroyUser Job.
 *
 * @author  macku99
 * @version 1.0
 */
class DestroyUser extends Job
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
        $user = User::findOrFail($this->id);
        $user->delete();

        event(new UserDestroyed($this->id));
    }

}