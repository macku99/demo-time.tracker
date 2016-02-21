<?php namespace App\Http\Controllers;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\TimeSheet\TimeSheetTransformer;
use App\DataModels\User\User;
use App\Jobs\TimeSheet\CreateTimeSheet;
use App\Jobs\TimeSheet\DestroyTimeSheet;
use App\Jobs\TimeSheet\UpdateTimeSheet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiUserTimeSheetController extends ApiController
{

    /**
     * Given an user return his timesheets list.
     *
     * @param  User $users
     * @return Response
     */
    public function index(User $users)
    {
        $this->authorize('index', [TimeSheet::class, $users->id]);

        $timeSheets = $users->timesheets()->with('user')->orderBy('date', 'DESC')->paginate(20);

        return $this->respondWithCollection($timeSheets, new TimeSheetTransformer);
    }

    /**
     * Given an user and one of his timesheets return the timesheet details.
     *
     * @param  User      $users
     * @param  TimeSheet $timesheets
     * @return Response
     */
    public function show(User $users, TimeSheet $timesheets)
    {
        $this->authorize('show', [TimeSheet::class, $users->id]);

        return $this->respondWithItem($timesheets, new TimeSheetTransformer);
    }

    /**
     *  Given an user store a timesheet against his records.
     *
     * @param  Request $request
     * @param  User    $users
     * @return Response
     */
    public function store(Request $request, User $users)
    {
        $this->authorize('store', [TimeSheet::class, $users->id]);

        $this->validate($request, $this->validationRules());

        $this->dispatch(
            new CreateTimeSheet($users->id, $request->get('date'), $request->get('hours'), $request->get('description'))
        );

        return $this->respondCreated();
    }

    /**
     * Update TimeSheet.
     *
     * @param  Request   $request
     * @param  User      $users
     * @param  TimeSheet $timesheets
     * @return Response
     */
    public function update(Request $request, User $users, TimeSheet $timesheets)
    {
        $this->authorize('update', $timesheets);

        $this->validate($request, $this->validationRules());

        $this->dispatch(
            new UpdateTimeSheet(
                $timesheets->id, $users->id,
                $request->get('date'), $request->get('hours'), $request->get('description')
            )
        );

        return $this->respondAccepted();
    }

    /**
     * Destroy TimeSheet.
     *
     * @param  User      $users
     * @param  TimeSheet $timesheets
     * @return Response
     */
    public function destroy(User $users, TimeSheet $timesheets)
    {
        $this->authorize('destroy', $timesheets);

        $this->dispatch(
            new DestroyTimeSheet($timesheets->id)
        );

        return $this->respondAccepted();
    }

    /**
     * Create or update validation rules.
     *
     * @return array
     */
    protected function validationRules()
    {
        return [
            'date'        => 'bail|required',
            'hours'       => 'bail|required|numeric|min:0.5|max:24',
            'description' => 'required',
        ];
    }

}