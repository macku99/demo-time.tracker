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
     * The id of the user being destroyed.
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