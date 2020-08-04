<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login.index');
    }

    public function ValidLogin(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.required' => 'Please enter email address.',
            'password.required' => 'Please enter password.'
        ]);

        // Check user exist or not
        if ($user = User::where('email', $request->email)->first())
        {
            if (Hash::check($request->get('password'), $user->password)) {
                Auth::login($user);
                return Redirect::route('task_details');
            }
            else
            {
                return Redirect::route('admin.login')->with('msg_fail','Invalid credentials');
            }
        } else {
            // No user found
            return Redirect::route('admin.login')->with('msg_fail','No user found');
        }
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $UserSocial = Socialite::driver('facebook')->user();
        

        $user = User::where('email',$UserSocial->email)->first();
        if($user){
            Auth::login($user);
            return redirect(route('task_details'));
        }else{
            $user_details = new User();
            $user_details->name = $UserSocial->name;
            $user_details->email = $UserSocial->email;
            $user_details->fb_profile_id = $UserSocial->id;
            $user_details->password = bcrypt('12345');
            $user_details->save();
            Auth::login($user_details);
            return redirect(route('task_details'));
        }

        //return $user->name;
        // $user->token;
    }
    public function logout()
    {
        Auth::logout();
        return Redirect::route('admin.login')->with('msg_success','User Logout successfully.');
    }
}
