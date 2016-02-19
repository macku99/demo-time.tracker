<?php namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\DataModels\User\UserTransformer;
use App\DataModels\User\User;
use App\Http\Requests;
use App\Jobs\User\CreateUser;
use App\Jobs\User\DestroyUser;
use App\Jobs\User\UpdateUser;
use Illuminate\Http\Response;

class ApiUsersController extends ApiController
{

    /**
     * Users list.
     *
     * @return Response
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::paginate(20);

        return $this->respondWithCollection($users, new UserTransformer);
    }

    /**
     * Show User.
     *
     * @param  User $users
     * @return Response
     */
    public function show(User $users)
    {
        $this->authorize('show', User::class);

        return $this->respondWithItem($users, new UserTransformer);
    }

    /**
     * Store User.
     *
     * @param  CreateUserRequest $request
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('store', User::class);

        $this->dispatch(
            new CreateUser(
                $request->role, $request->name, $request->email, $request->password,
                $request->preferredDailyHours
            )
        );

        return $this->respondCreated();
    }

    /**
     * Update User.
     *
     * @param  UpdateUserRequest $request
     * @param  User              $users
     * @return Response
     */
    public function update(UpdateUserRequest $request, User $users)
    {
        $this->authorize('update', $users);

        $this->dispatch(
            new UpdateUser(
                $users->id, $request->role, $request->name, $request->email, $request->password,
                $request->preferredDailyHours
            )
        );

        return $this->respondAccepted();
    }

    /**
     * Destroy User.
     *
     * @param  User $users
     * @return Response
     */
    public function destroy(User $users)
    {
        $this->authorize('destroy', $users);

        $this->dispatch(
            new DestroyUser($users->id)
        );

        return $this->respondAccepted();
    }

}