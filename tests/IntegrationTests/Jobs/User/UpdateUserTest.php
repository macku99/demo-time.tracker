<?php namespace Tests\Integration\Jobs\User;

use App\DataModels\User\User;
use App\Jobs\User\UpdateUser;
use Factory;
use TestCase;

class UpdateUserTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_update_user_job()
    {
        $user = $this->make(User::class);

        $this->dispatch(
            new UpdateUser($user->id, 'admin', 'Joe Doe', 'joe@example.com')
        );

        $this->seeInDatabase('users', [
            'id'    => $user->id,
            'role'  => 'admin',
            'name'  => 'Joe Doe',
            'email' => 'joe@example.com',
        ]);
    }

    /**
     * @test
     * @expectedException \Assert\InvalidArgumentException
     */
    public function it_does_not_allow_email_duplication()
    {
        $this->make(User::class, [
            'email' => 'joe@example.com',
        ]);

        $user = $this->make(User::class);

        $this->dispatch(
            new UpdateUser($user->id, 'admin', 'Joe Doe', 'joe@example.com')
        );
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'role'     => 'admin',
            'name'     => $this->fake->name,
            'email'    => $this->fake->email,
            'password' => $this->fake->password,
        ];
    }

}