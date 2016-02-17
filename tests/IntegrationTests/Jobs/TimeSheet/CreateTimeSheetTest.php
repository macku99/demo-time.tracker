<?php namespace Tests\Integration\Jobs\TimeSheet;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use App\Jobs\TimeSheet\CreateTimeSheet;
use Factory;
use TestCase;

class CreateTimeSheetTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_create_timesheet_job()
    {
        $data = $this->getStub();

        $this->dispatch(
            new CreateTimeSheet($data['user_id'], $data['date'], $data['hours'], $data['description'])
        );

        $this->seeInDatabase('timesheets', [
            'user_id'     => $data['user_id'],
            'date'        => $data['date'],
            'hours'       => $data['hours'],
            'description' => $data['description'],
        ]);
    }

    /**
     * @test
     * @expectedException \Assert\InvalidArgumentException
     */
    public function it_does_not_allow_more_than_24_worked_hours_per_day()
    {
        $user = factory(User::class)->create();
        $this->make(TimeSheet::class, [
            'user_id' => $user->id,
            'date'    => '2016-01-01',
            'hours'   => 20,
        ]);

        $this->dispatch(
            new CreateTimeSheet($user->id, '2016-01-01', 5, 'some description')
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