<?php namespace Tests\Integration\Jobs\User;

use App\DataModels\User\User;
use App\Jobs\User\DestroyUser;
use Factory;
use TestCase;

class DestroyUserTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_destroy_user_job()
    {
        $user = $this->make(User::class);

        $this->dispatch(
            new DestroyUser($user->id)
        );

        $this->dontSeeInDatabase('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'name'     => $this->fake->name,
            'email'    => $this->fake->email,
            'password' => $this->fake->password,
        ];
    }

}