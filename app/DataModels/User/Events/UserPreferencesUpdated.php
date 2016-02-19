<?php namespace App\DataModels\User\Events;

use App\DataModels\User\User;
use Illuminate\Queue\SerializesModels;

/**
 * UserPreferencesUpdated Event.
 *
 * @author  macku99
 * @version 1.0
 */
class UserPreferencesUpdated
{

    use SerializesModels;

    /**
     * The user that has preferences updated.
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