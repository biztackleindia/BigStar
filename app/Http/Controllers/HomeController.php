<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\User;
use Hash;
use Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function DeleteUser(){
        return view('web-views.userdelete');
    }

    public function DeleteUserPost(Request $request){
        // dd($request->get('email'));
        $user  = User::where('email', $request->get("email"))->first();
        if($user == null){
            return Redirect::back()->withErrors(['msg' => 'User Doesnot Exists']);
        }
        // dd($user);
        if(Hash::check($request->get("password"),$user->getAuthPassword())){
            //Update User to inactive status
            $user->is_active = false;
            $user->save();
            return Redirect::back()->withErrors(['msg' => 'User Deleted Successfully !!!!!']);
        }else{
            return Redirect::back()->withErrors(['msg' => 'User Doesnot Exists']);
        }
    }
}
