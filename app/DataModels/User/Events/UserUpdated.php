<?php namespace App\DataModels\User\Events;

use App\DataModels\User\User;
use Illuminate\Queue\SerializesModels;

/**
 * UserUpdated Event.
 *
 * @author  macku99
 * @version 1.0
 */
class UserUpdated
{

    use SerializesModels;

    /**
     * The user being updated.
     *
     * @var User
     */
    public $user;

    /**
     * @param User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

}