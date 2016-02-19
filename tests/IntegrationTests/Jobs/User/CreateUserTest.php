<?php namespace Tests\Integration\Jobs\User;

use App\DataModels\User\User;
use App\Jobs\User\CreateUser;
use Factory;
use TestCase;

class CreateUserTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_create_user_job()
    {
        $data = $this->getStub();

        $this->dispatch(
            new CreateUser($data['role'], $data['name'], $data['email'], $data['password'])
        );

        $this->seeInDatabase('users', [
            'role'  => $data['role'],
            'name'  => $data['name'],
            'email' => $data['email'],
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

        $data = $this->getStub();

        $this->dispatch(
            new CreateUser($data['role'], $data['name'], 'joe@example.com', $data['password'])
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