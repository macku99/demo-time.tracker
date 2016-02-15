<?php namespace App\Http\Controllers;

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

        return view('timesheets', compact('userId', 'userName'));
    }

    /**
     * Show the application account settings page.
     *
     * @return Response
     */
    public function account()
    {
        return view('account');
    }

}
