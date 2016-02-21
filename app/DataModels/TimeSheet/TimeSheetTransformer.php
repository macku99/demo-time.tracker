<?php namespace App\DataModels\TimeSheet;

use App\DataModels\TimeSheetsSummary\TimeSheetsSummaryRepository;
use App\DataModels\User\UserTransformer;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

/**
 * TimeSheet Model Transformer.
 *
 * @author  macku99
 * @version 1.0
 */
class TimeSheetTransformer extends TransformerAbstract
{

    /**
     * Include resources without needing it to be requested.
     *
     * @var array
     */
    protected $defaultIncludes = [
        'user',
    ];

    /**
     * Transform a timesheet model.
     *
     * @param  TimeSheet $timesheet
     * @return array
     */
    public function transform(TimeSheet $timesheet)
    {
        if (is_null($timesheet)) {
            return [];
        }

        $timeSheetsSummaryRepository = app(TimeSheetsSummaryRepository::class);
        $totalHoursWorkedOnTheDate = $timeSheetsSummaryRepository->totalHoursWorkedByUserForDate(
            $timesheet->user_id,
            (new Carbon($timesheet->date))->toDateString()
        );

        return [
            'id'                        => (int) $timesheet->id,
            'date'                      => $timesheet->date,
            'description'               => trim($timesheet->description),
            'hours'                     => (float) number_format($timesheet->hours, 1),
            'totalHoursWorkedOnTheDate' => (float) number_format($totalHoursWorkedOnTheDate, 1),
            'createdAt'                 => $timesheet->created_at->diffForHumans(),
            'updatedAt'                 => $timesheet->updated_at->diffForHumans(),
        ];
    }

    /**
     * Include timesheet user.
     *
     * @param  TimeSheet $timesheet
     * @return array
     */
    public function includeUser(TimeSheet $timesheet)
    {
        return $this->item($timesheet->user, new UserTransformer);
    }

}