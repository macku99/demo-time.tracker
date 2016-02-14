<?php namespace App\DataModels\User\Events;

/**
 * UserDestroyed Event.
 *
 * @author  macku99
 * @version 1.0
 */
class UserDestroyed
{

    /**
     * The user id being destroyed.
     *
     * @var int
     */
    public $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

}