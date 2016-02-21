<?php namespace App\Http\Controllers;

use App\DataModels\TimeSheet\TimeSheet;
use App\DataModels\User\User;
use App\Http\Requests;
use Illuminate\Http\Response;

class PageController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Show the application users page.
     *
     * @return Response
     */
    public function users()
    {
        $this->authorize('index', User::class);

        return view('users');
    }

    /**
     * Show the application timesheets page.
     *
     * @param  User $users
     * @return Response
     */
    public function timesheets(User $users)
    {
        $userId = $users->id;
        $userName = $users->name;

        $this->authorize('index', [TimeSheet::class, $userId]);

        return view('timesheets', compact('userId', 'userName'));
    }

    /**
     * Show the application timesheets page.
     *
     * @param  User   $users
     * @param  string $dateRange
     * @return Response
     */
    public function exportTimesheets(User $users, $dateRange = null)
    {
        $timeSheets = $users->timesheets()
                            ->orderBy('date', 'DESC')
                            ->get();

        return view('exported-timesheets', compact('timeSheets'));
    }

}
