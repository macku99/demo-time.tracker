<?php namespace Tests\Acceptance;

use App\DataModels\User\User;
use TestCase;

class ApiUsersControllerTest extends TestCase
{

    /**
     * @test
     */
    public function it_fetches_users()
    {
        factory(User::class, 10)->create();

        $this->json('GET', 'api/users')
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'total' => 10,
             ]);
    }

    /**
     * @test
     */
    public function it_fetches_a_single_user()
    {
        $user = factory(User::class)->create();

        $this->json('GET', 'api/users/' . $user->id)
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'id'    => $user->id,
                 'email' => $user->email,
             ]);
    }

    /**
     * @test
     */
    public function it_throws_404_if_a_user_is_not_found()
    {
        $this->json('GET', 'api/users/NON_EXISTENT_USER')
             ->seeStatusCode(404)
             ->shouldReturnJson();
    }

    /**
     * @test
     */
    public function it_creates_a_new_user_using_valid_parameters()
    {
        $this->post('api/users', $this->getStub())
             ->seeStatusCode(201);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_the_creation_of_new_user_fails_validation()
    {
        $this->json('POST', "api/users", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_updates_an_existent_user_given_valid_parameters()
    {
        $user = factory(User::class)->create();

        $this->put("api/users/{$user->id}", $this->getStub())
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_user_to_be_updated_is_not_found()
    {
        $this->put("api/users/NOT_FOUND_USER", $this->getStub())
             ->seeStatusCode(404);
    }

    /**
     *
     */
    public function it_throws_a_422_if_the_update_of_an_existent_user_fails_validation()
    {
        $user = factory(User::class)->create();

        $this->json('PUT', "api/users/{$user->id}", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_destroys_an_user()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', "api/users/{$user->id}")
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_found_when_destroying_an_user()
    {
        $this->json('DELETE', "api/users/NOT_EXISTENT_USER")
             ->seeStatusCode(404);
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'name'                  => 'Joe Doe',
            'email'                 => 'joe@email.com',
            'password'              => 'Qw3rt!',
            'password_confirmation' => 'Qw3rt!',
        ];
    }

}