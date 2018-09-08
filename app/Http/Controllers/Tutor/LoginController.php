<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/tutor';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('login', ['url' => url('tutor/login')]);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->redirectTo = url('tutor');
    }


    public function logout(Request $request)
    {
        Auth::guard('tutor')->logout();
        $request->session()->invalidate();
        return redirect('tutor/login');

    }

    protected function guard()
    {
        return Auth::guard('tutor');
    }

    protected function authenticated(Request $request, $user)
    {
        if($user){
            return redirect('tutor');
        }
    }
}