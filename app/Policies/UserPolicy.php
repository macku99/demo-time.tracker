<?php namespace App\Policies;

use App\DataModels\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;

    /**
     * Determine if the given user can view users list.
     *
     * @return bool
     */
    public function index()
    {
        return false;
    }

    /**
     * Determine if the given user can view another user details.
     *
     * @return bool
     */
    public function show()
    {
        return false;
    }

    /**
     * Determine if the given user can create another user.
     *
     * @return bool
     */
    public function store()
    {
        return false;
    }

    /**
     * Determine if the given user can update another user.
     *
     * @param  User $user
     * @param  User $anotherUser
     * @return bool
     */
    public function update(User $user, User $anotherUser)
    {
        return $user->id == $anotherUser->id;
    }

    /**
     * Determine if the given user can delete another user.
     *
     * @param  User $user
     * @param  User $anotherUser
     * @return bool
     */
    public function destroy(User $user, User $anotherUser)
    {
        return false;
    }

    /**
     * Determine if the given user can update user preferences.
     *
     * @param  User $user
     * @param  User $anotherUser
     * @return bool
     */
    public function updateUserPreferences(User $user, User $anotherUser)
    {
        return $user->id == $anotherUser->id;
    }

    /**
     * The admin users have all abilities.
     *
     * @param  User   $user
     * @param  string $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin() && ! in_array($ability, ['update-user-preferences'])) {
            return true;
        }
    }

}