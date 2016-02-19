<?php namespace App\Http\Requests\User;

use App\Http\Requests\Request;

/**
 * UpdateUser Request.
 *
 * @author  macku99
 * @version 1.0
 *
 * @property string $role
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int    $preferredDailyHours
 */
class UpdateUserRequest extends Request
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'role'     => 'bail|required|in:regular,admin',
            'name'     => 'bail|required|max:255',
            'email'    => 'bail|required|email|max:255|unique:users,email,' . $this->route('users')->id,
            'password' => 'bail|confirmed|min:6',
        ];
    }

}