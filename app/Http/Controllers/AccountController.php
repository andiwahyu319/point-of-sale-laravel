<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select("name")->get();
        return view('hasLogin.account.index', compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->id == 1) {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users",
                "password" => "required|string|min:8|confirmed",
            ]);
            $newacc = new User;
            $newacc->name = $request->name;
            $newacc->email = $request->email;
            $newacc->password = Hash::make($request->password);
            $newacc->save();
            return redirect("account");
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->type == "account") {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users,email," . $request->user()->id,
            ]);
            $request->user()->update([
                "name" => $request->name,
                "email" => $request->email
            ]);
        } else if($request->type == "password") {
            $this->validate($request, [
                "password" => ["required", "string", "min:8", "confirmed"],
                "old_password" => ["required", function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, Auth::user()->password)) {
                        return $fail(__("The old password is incorrect."));
                    }
                }]
            ]);
            $request->user()->update($request->password);
        }
        return redirect("account");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = User::find(Auth::user()->id);
        Auth::logout();
        $user->delete();
        return redirect("login");
        
    }
}
