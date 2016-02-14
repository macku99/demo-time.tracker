<?php namespace App\DataModels\User;

/**
 * UserEmailIsUnique Specification.
 *
 * @author  macku99
 * @version 1.0
 */
class UserEmailIsUniqueSpecification
{

    /**
     * Check to see if the specification is satisfied
     *
     * @param  string $userId
     * @param  string $email
     * @return boolean
     */
    public function isSatisfiedBy($userId = null, $email)
    {
        $exists = User::where('email', $email)
                      ->where(function ($query) use ($userId) {
                          if ( ! is_null($userId)) {
                              $query->where('id', '<>', $userId);
                          }
                      })
                      ->count();

        return $exists > 0 ? false : true;
    }

}