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
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Assert\that((new UserEmailIsUniqueSpecification)->isSatisfiedBy(null, $this->email))->true();

        $user = new User();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password;

        $user->save();

        event(new UserCreated($user));
    }

}