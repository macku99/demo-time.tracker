<?php namespace App\Http\Requests\User;

use App\Http\Requests\Request;

/**
 * UpdateUser Request.
 *
 * @author  macku99
 * @version 1.0
 *
 * @property string $name
 * @property string $email
 * @property string $password
 */
class UpdateUserRequest extends Request
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'     => 'bail|required|max:255',
            'email'    => 'bail|required|email|max:255|unique:users,email,' . $this->get('id'),
            'password' => 'bail|required|confirmed|min:6',
        ];
    }

}