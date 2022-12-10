<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class FasilitatorLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:fasilitator')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.fasilitator.login');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
        // Attempt to log the user in
        if (Auth::guard('fasilitator')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended(route('fasilitator.dashboard.index'));
        }
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('fasilitator')->logout();
        return redirect('fasilitator/login');
    }
}
