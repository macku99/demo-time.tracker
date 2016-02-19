<?php namespace App\Http\Controllers\Auth;

use App\DataModels\User\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var JWTAuth
     */
    private $JWTAuth;

    /**
     * Create a new authentication controller instance.
     *
     * @param JWTAuth $JWTAuth
     */
    public function __construct(JWTAuth $JWTAuth)
    {
        $this->JWTAuth = $JWTAuth;

        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  Request         $request
     * @param  Authenticatable $user
     * @return Response
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        // generate a JWT token
        //$this->JWTAuth->fromUser($user);

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\DataModels\User\User
     */
    protected function create(array $data)
    {
        return User::create([
            'role'     => 'regular',
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
