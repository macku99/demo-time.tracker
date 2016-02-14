<?php namespace Tests\Acceptance;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use Factory;
use TestCase;

class ApiUserTimeSheetControllerTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_fetches_given_user_timesheets()
    {
        $user = factory(User::class)->create();
        factory(TimeSheet::class, 10)->create([
            'user_id' => $user->id,
        ]);

        $this->json('GET', "api/users/{$user->id}/timesheets")
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'total' => 10,
             ]);
    }

    /**
     * @test
     */
    public function it_fetches_given_user_single_timesheet()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->json('GET', "api/users/{$user->id}/timesheets/{$timeSheet->id}")
             ->seeStatusCode(200)
             ->shouldReturnJson([
                 'id'          => $timeSheet->id,
                 'date'        => $timeSheet->date,
                 'hours'       => $timeSheet->hours,
                 'description' => $timeSheet->description,
             ]);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_found()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->json('GET', "api/users/NON_EXISTENT_USER/timesheets/{$timeSheet->id}")
             ->seeStatusCode(404)
             ->shouldReturnJson();
    }

    /**
     * @test
     */
    public function it_throws_404_if_a_timesheet_is_not_found_for_the_given_user()
    {
        $user = factory(User::class)->create();

        $this->json('GET', "api/users/{$user->id}/timesheets/NON_EXISTENT_TIMESHEET")
             ->seeStatusCode(404)
             ->shouldReturnJson();
    }

    /**
     * @test
     */
    public function it_creates_a_new_timesheet_for_the_given_user_using_valid_parameters()
    {
        $user = factory(User::class)->create();

        $this->post("api/users/{$user->id}/timesheets", $this->getStub())
             ->seeStatusCode(201);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_existent_when_creating_a_timesheet()
    {
        $this->json('POST', "api/users/NOT_EXISTENT_USER/timesheets", [])
             ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_the_creation_of_new_timesheet_for_the_given_user_fails_validation()
    {
        $user = factory(User::class)->create();

        $this->json('POST', "api/users/{$user->id}/timesheets", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_updates_an_existent_timesheet_for_the_given_user_using_valid_parameters()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->put("api/users/{$user->id}/timesheets/{$timeSheet->id}", $this->getStub())
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_existent_when_updating_a_timesheet()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->put("api/users/NOT_EXISTENT_USER/timesheets/{$timeSheet->id}", $this->getStub())
             ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_timesheet_to_be_updated_is_not_found()
    {
        $user = factory(User::class)->create();

        $this->put("api/users/{$user->id}/timesheets/NOT_EXISTENT_TIMESHEET", $this->getStub())
             ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function it_throws_a_422_if_the_update_of_an_existent_timesheet_for_a_given_user_fails_validation()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->json('PUT', "api/users/{$user->id}/timesheets/{$timeSheet->id}", [])
             ->seeStatusCode(422);
    }

    /**
     * @test
     */
    public function it_destroys_a_timesheet_for_a_given_user()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->json('DELETE', "api/users/{$user->id}/timesheets/{$timeSheet->id}")
             ->seeStatusCode(202);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_given_user_is_not_found_when_destroying_a_timesheet()
    {
        $user = factory(User::class)->create();
        $timeSheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->json('DELETE', "api/users/NOT_EXISTENT_USER/timesheets/{$timeSheet->id}")
             ->seeStatusCode(404);
    }

    /**
     * @test
     */
    public function it_throws_404_if_the_timesheet_to_be_destroyed_is_not_found()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', "api/users/{$user->id}/timesheets/NOT_EXISTENT_TIMESHEET")
             ->seeStatusCode(404);
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'date'        => $this->fake->dateTimeThisYear->format('Y-m-d'),
            'hours'       => 1,
            'description' => $this->fake->paragraph(5),
        ];
    }

}