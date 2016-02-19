<?php namespace Tests\Integration\Jobs\TimeSheet;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use Carbon\Carbon;
use Factory;
use TestCase;

class TimeSheetsSummaryTest extends TestCase
{

    /**
     * @test
     */
    public function it_inserts_a_new_timesheets_summary_row_when_a_new_timesheet_is_added()
    {
        $user = factory(User::class)->create();
        $timesheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
        ]);

        $this->seeInDatabase('timesheets_summary', [
            'user_id' => $timesheet->user_id,
            'date'    => (new Carbon($timesheet->date))->toDateString(),
            'hours'   => $timesheet->hours,
        ]);
    }

    /**
     * @test
     */
    public function it_increments_hours_when_a_new_timesheet_is_added_but_it_exists_in_timesheets_summary()
    {
        $user = factory(User::class)->create();
        factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 10,
        ]);
        factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 2,
        ]);

        $this->seeInDatabase('timesheets_summary', [
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 12,
        ]);
    }

    /**
     * @test
     */
    public function it_increments_hours_when_a_timesheet_is_updated_with_an_amount_of_hours_greater_than_before()
    {
        $user = factory(User::class)->create();
        factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 10,
        ]);
        $timesheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 2,
        ]);

        $timesheet->hours = 6;
        $timesheet->save();

        $this->seeInDatabase('timesheets_summary', [
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 16,
        ]);
    }

    /**
     * @test
     */
    public function it_decrements_hours_when_a_timesheet_is_updated_with_an_amount_of_hours_lesser_than_before()
    {
        $user = factory(User::class)->create();
        factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 10,
        ]);
        $timesheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 4,
        ]);

        $timesheet->hours = 2;
        $timesheet->save();

        $this->seeInDatabase('timesheets_summary', [
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 12,
        ]);
    }

    /**
     * @test
     */
    public function it_decrements_hours_when_a_timesheet_is_deleted()
    {
        $user = factory(User::class)->create();
        factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 10,
        ]);
        $timesheet = factory(TimeSheet::class)->create([
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 4,
        ]);

        $timesheet->delete();

        $this->seeInDatabase('timesheets_summary', [
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 10,
        ]);
    }

}