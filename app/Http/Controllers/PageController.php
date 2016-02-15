<?php namespace App\Http\Controllers;

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
     * @return Response
     */
    public function timesheets()
    {
        return view('timesheets');
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
