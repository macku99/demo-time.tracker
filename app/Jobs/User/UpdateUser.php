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
     * @param int    $id
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct($id, $name, $email, $password = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        \Assert\that((new UserEmailIsUniqueSpecification)->isSatisfiedBy($this->id, $this->email))->true();

        $user = User::findOrFail($this->id);

        $user->name = $this->name;
        $user->email = $this->email;
        if ( ! is_null($this->password)) {
            $user->password = $this->password;
        }

        $user->save();

        event(new UserUpdated($user));
    }

}