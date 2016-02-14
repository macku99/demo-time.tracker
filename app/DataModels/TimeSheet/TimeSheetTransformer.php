<?php namespace App\DataModels\TimeSheet;

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

        return [
            'id'          => (int) $timesheet->id,
            'date'        => $timesheet->date,
            'description' => trim($timesheet->description),
            'hours'       => (int) $timesheet->hours,
            'createdAt'   => $timesheet->created_at->diffForHumans(),
            'updatedAt'   => $timesheet->updated_at->diffForHumans(),
        ];
    }

}