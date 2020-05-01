<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class CustomAuthController extends Controller
{
    public function adults(){
        return view('customAuth.index');
    }

    public function site(){
        return view('site');
    }

    public function admin(){
        return view('admin');
    }

    public function adminLogin(){
        return view('auth.adminLogin');
    }

    public function checkAdminLogin(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) { //means check if this email and pass in that guard table

            return redirect()->intended('/admin'); //go to admin route
        }
        return back()->withInput($request->only('email')); //return with input
    }
}
