<?php namespace App\DataModels\User;

use App\DataModels\TimeSheet\TimeSheetTransformer;
use League\Fractal\TransformerAbstract;

/**
 * User Model Transformer.
 *
 * @author  macku99
 * @version 1.0
 */
class UserTransformer extends TransformerAbstract
{

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'timeSheets',
    ];

    /**
     * Transform an user model.
     *
     * @param  User $user
     * @return array
     */
    public function transform(User $user)
    {
        if (is_null($user)) {
            return [];
        }

        return [
            'id'                  => (int) $user->id,
            'role'                => $user->role,
            'name'                => trim($user->name),
            'email'               => $user->email,
            'preferredDailyHours' => (int) $user->preferred_daily_hours,
            'createdAt'           => $user->created_at->diffForHumans(),
            'updatedAt'           => $user->updated_at->diffForHumans(),
        ];
    }

    /**
     * Include user timesheets.
     *
     * @param  User $user
     * @return array
     */
    public function includeTimeSheets(User $user)
    {
        return $this->collection($user->timesheets, new TimeSheetTransformer);
    }

}