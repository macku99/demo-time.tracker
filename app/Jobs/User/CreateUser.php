<?php namespace App\Jobs\User;

use App\DataModels\User\Events\UserCreated;
use App\DataModels\User\UserEmailIsUniqueSpecification;
use App\DataModels\User\User;
use App\Jobs\Job;

/**
 * CreateUser Job.
 *
 * @author  macku99
 * @version 1.0
 */
class CreateUser extends Job
{

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
    private $preferredDailyHours;

    /**
     * @param string $role
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int    $preferredDailyHours
     */
    public function __construct($role, $name, $email, $password, $preferredDailyHours = null)
    {
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
        \Assert\that((new UserEmailIsUniqueSpecification)->isSatisfiedBy(null, $this->email))->true();

        $user = new User();

        $user->role = $this->role;
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = bcrypt($this->password);
        if ( ! is_null($this->preferredDailyHours)) {
            $user->preferred_daily_hours = $this->preferredDailyHours;
        }

        $user->save();

        event(new UserCreated($user));
    }

}