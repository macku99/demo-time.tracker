<?php namespace App\Http\Controllers;

use App\DataModels\User\Events\UserPreferencesUpdated;
use App\DataModels\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiUserPreferencesController extends ApiController
{

    /**
     * Update User Preferences.
     *
     * @param  Request $request
     * @param  User    $users
     * @return Response
     */
    public function update(Request $request, User $users)
    {
        $this->authorize('update-user-preferences', $users);

        $this->validate($request, [
            'preferredDailyHours' => 'required|int',
        ]);

        $users->preferred_daily_hours = $request->get('preferredDailyHours');
        $users->save();

        event(new UserPreferencesUpdated($users));

        return $this->respondAccepted();
    }

}