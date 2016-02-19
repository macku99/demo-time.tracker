<?php namespace App\Jobs\User;

use App\DataModels\User\Events\UserUpdated;
use App\DataModels\User\UserEmailIsUniqueSpecification;
use App\DataModels\User\User;
use App\Jobs\Job;

/**
 * UpdateUser Job.
 *
 * @author  macku99
 * @version 1.0
 */
class UpdateUser extends Job
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var int
     */
    protected $preferredDailyHours;

    /**
     * @param int    $id
     * @param string $role
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int    $preferredDailyHours
     */
    public function __construct($id, $role, $name, $email, $password = null, $preferredDailyHours = null)
    {
        $this->id = $id;
        $this->role = $role;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->preferredDailyHours = $preferredDailyHours;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Assert\that((new UserEmailIsUniqueSpecification)->isSatisfiedBy($this->id, $this->email))->true();

        $user = User::findOrFail($this->id);

        $user->role = $this->role;
        $user->name = $this->name;
        $user->email = $this->email;
        if ( ! is_null($this->password)) {
            $user->password = bcrypt($this->password);
        }
        if ( ! is_null($this->preferredDailyHours)) {
            $user->preferred_daily_hours = $this->preferredDailyHours;
        }

        $user->save();

        event(new UserUpdated($user));
    }

}