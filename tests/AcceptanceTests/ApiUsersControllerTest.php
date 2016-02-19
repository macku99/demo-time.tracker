<?php namespace Tests\Acceptance;

use App\DataModels\User\User;
use TestCase;
use UsesJWTTokens;

class ApiUsersControllerTest extends TestCase
{

    use UsesJWTTokens;

    /**
     * @test
     */
    public function it_fetches_users()
    {
        factory(User::class, 10)->create();

        $this->jwtJson('GET', 'api/users')
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'total' => 10 + 1,
             ]);
    }

    /**
     * @test
     */
    public function it_does_not_allow_regular_users_to_fetch_users()
    {
        $this->withAuthUser(factory(User::class, 'regular')->create())
             ->jwtJson('GET', 'api/users')
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_a_400_if_the_request_does_not_include_a_JWT_token()
    {
        factory(User::class, 10)->create();

        $this->json('GET', 'api/users')
             ->seeStatusCode(400)
             ->shouldReturnJson([
                 'error' => 'token_not_provided',
             ]);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_user()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('GET', 'api/users/' . $user->id)
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'id'    => $user->id,
                 'email' => $user->email,
             ]);
    }

    /**
     * @test
     */
    public function it_does_not_allow_regular_users_to_fetch_a_single_user()
    {
        $user = factory(User::class)->create();

        $this->withAuthUser(factory(User::class, 'regular')->create())
             ->jwtJson('GET', 'api/users/' . $user->id)
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_404_if_a_user_is_not_found()
    {
        $this->jwtJson('GET', 'api/users/NON_EXISTENT_USER')
             ->seeStatusCode(404)
             ->shouldReturnJson();
    }

    /**
     * @test
     */
    public function it_creates_a_new_user_using_valid_parameters()
    {
        $this->jwtJson('POST', 'api/users', $this->getStub())
             ->seeStatusCode(201);
    }

    /**
     * @test
     */
    public function it_does_not_allow_regular_users_to_create_a_new_user()
    {
        $this->withAuthUser(factory(User::class, 'regular')->create())
             ->jwtJson('POST', 'api/users', $this->getStub())
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_the_creation_of_new_user_fails_validation()
    {
        $this->jwtJson('POST', "api/users", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_updates_an_existent_user_given_valid_parameters()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('PUT', "api/users/{$user->id}", $this->getStub())
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_does_not_allow_regular_users_to_update_an_existent_user()
    {
        $user = factory(User::class)->create();

        $this->withAuthUser(factory(User::class, 'regular')->create())
             ->jwtJson('PUT', "api/users/{$user->id}", $this->getStub())
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_user_to_be_updated_is_not_found()
    {
        $this->jwtJson('PUT', "api/users/NOT_FOUND_USER", $this->getStub())
             ->seeStatusCode(404);
    }

    /**
     *
     */
    public function it_throws_a_422_if_the_update_of_an_existent_user_fails_validation()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('PUT', "api/users/{$user->id}", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_removes_an_existent_user()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('DELETE', "api/users/{$user->id}")
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_does_not_allow_regular_users_to_remove_an_existent_user()
    {
        $user = factory(User::class)->create();

        $this->withAuthUser(factory(User::class, 'regular')->create())
             ->jwtJson('DELETE', "api/users/{$user->id}")
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_found_when_removing_an_user()
    {
        $this->jwtJson('DELETE', "api/users/NOT_EXISTENT_USER")
             ->seeStatusCode(404);
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'role'                  => $this->fake->randomElement(['regular', 'admin']),
            'name'                  => 'Joe Doe',
            'email'                 => 'joe@email.com',
            'password'              => 'Qw3rt!',
            'password_confirmation' => 'Qw3rt!',
        ];
    }

}