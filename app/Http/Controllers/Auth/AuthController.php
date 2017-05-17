<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Timeline;
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
    protected $redirectTo = '/';
    protected $username = "no_id";

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'no_id' => 'required|min:6|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $status = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_id' => $data['no_id'],
            'password' => bcrypt($data['password']),
            'id_role' => $data['role'],
            'image' => 'dist/img/noprofile.gif',
            'status' => 0
        ]);

        if ($status) {
            $user = User::where('email',$data['email'])->first();
            Timeline::create([
                'id_user' => $user->id_user,
                'aksi' => 'melakukan registrasi sebagai '.($user->id_role==2?'Editor':'Mahasiswa'),
                'href' => ($user->id_role==2?'editor':'mahasiswa').'/'.$user->id_user
            ]);
        }

        return $status;
    }
}
