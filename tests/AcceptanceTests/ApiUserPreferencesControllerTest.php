<?php namespace Tests\Acceptance;

use App\DataModels\User\User;
use TestCase;
use UsesJWTTokens;

class ApiUserPreferencesControllerTest extends TestCase
{

    use UsesJWTTokens;

    /**
     * @test
     */
    public function it_updates_an_existent_user_preferences_given_valid_parameters()
    {
        $user = factory(User::class)->create();
        $data = $this->getStub();

        $this->withAuthUser($user)
             ->jwtJson('PUT', "api/users/preferences/{$user->id}", $data)
             ->seeStatusCode(202);

        $this->seeInDatabase('users', [
            'id'                    => $user->id,
            'preferred_daily_hours' => $data['preferredDailyHours'],
        ]);
    }

    /**
     * @test
     */
    public function it_does_not_allow_users_to_update_other_user_preferences()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('PUT', "api/users/preferences/{$user->id}", $this->getStub())
             ->seeStatusCode(403);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_user_to_have_preferences_updated_is_not_found()
    {
        $this->jwtJson('PUT', "api/users/preferences/NOT_FOUND_USER", $this->getStub())
             ->seeStatusCode(404);
    }

    /**
     *
     */
    public function it_throws_a_422_if_the_update_of_an_existent_user_preferences_fails_validation()
    {
        $user = factory(User::class)->create();

        $this->jwtJson('PUT', "api/users/preferences/{$user->id}", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_throws_a_400_if_the_request_does_not_include_a_JWT_token()
    {
        $user = factory(User::class)->create();

        $this->json('PUT', "api/users/preferences/{$user->id}", $this->getStub())
             ->seeStatusCode(400)
             ->shouldReturnJson([
                 'error' => 'token_not_provided',
             ]);
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'preferredDailyHours' => rand(1, 24),
        ];
    }

}