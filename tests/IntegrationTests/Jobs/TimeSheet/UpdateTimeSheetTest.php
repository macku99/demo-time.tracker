<?php namespace Tests\Integration\Jobs\TimeSheet;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use App\Jobs\TimeSheet\UpdateTimeSheet;
use Factory;
use TestCase;

class UpdateTimeSheetTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_update_timesheet_job()
    {
        $timeSheet = $this->make(TimeSheet::class);

        $this->dispatch(
            new UpdateTimeSheet($timeSheet->id, $timeSheet->user_id, '2016-01-01', 15, 'some description')
        );

        $this->seeInDatabase('timesheets', [
            'id'          => $timeSheet->id,
            'user_id'     => $timeSheet->user_id,
            'date'        => '2016-01-01',
            'hours'       => 15,
            'description' => 'some description',
        ]);
    }

    /**
     * @test
     * @expectedException \Assert\InvalidArgumentException
     */
    public function it_does_not_allow_more_than_24_worked_hours_per_day()
    {
        $date = '2016-01-01';

        $user = factory(User::class)->create();
        $this->make(TimeSheet::class, [
            'user_id' => $user->id,
            'date'    => $date,
            'hours'   => 20,
        ]);

        $timeSheet = $this->make(TimeSheet::class, [
            'user_id' => $user->id,
            'date'    => $date,
            'hours'   => 2,
        ]);

        $this->dispatch(
            new UpdateTimeSheet($timeSheet->id, $timeSheet->user_id, $date, 10, 'some description')
        );
    }

    /**
     * @return array
     */
    protected function getStub()
    {
        return [
            'user_id'     => factory(User::class)->create()->id,
            'date'        => $this->fake->dateTimeThisYear->format('Y-m-d'),
            'hours'       => 1,
            'description' => $this->fake->paragraph(5),
        ];
    }

}