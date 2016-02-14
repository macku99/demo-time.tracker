<?php namespace Tests\Integration\Jobs\TimeSheet;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use App\Jobs\TimeSheet\DestroyTimeSheet;
use Factory;
use TestCase;

class DestroyTimeSheetTest extends TestCase
{

    use Factory;

    /**
     * @test
     */
    public function it_handles_destroy_timesheet_job()
    {
        $timeSheet = $this->make(TimeSheet::class);

        $this->dispatch(
            new DestroyTimeSheet($timeSheet->id)
        );

        $this->dontSeeInDatabase('timesheets', [
            'id' => $timeSheet->id,
        ]);
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