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
        $this->dispatch(
            new CreateUser($request->name, $request->email, $request->password, $request->preferredDailyHours)
        );

        return $this->respondCreated();
    }

    /**
     * Update User.
     *
     * @param  UpdateUserRequest $request
     * @param  int               $userId
     * @return Response
     */
    public function update(UpdateUserRequest $request, $userId)
    {
        $this->dispatch(
            new UpdateUser($userId, $request->name, $request->email, $request->password, $request->preferredDailyHours)
        );

        return $this->respondAccepted();
    }

    /**
     * Destroy User.
     *
     * @param  int $userId
     * @return Response
     */
    public function destroy($userId)
    {
        $this->dispatch(
            new DestroyUser($userId)
        );

        return $this->respondAccepted();
    }

}