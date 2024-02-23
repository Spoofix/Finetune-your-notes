<?php

namespace App\Http\Controllers\API\Authentication;

use App\Models\User;
use App\Models\UserSwitch;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthenticationController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return $this->success([
            'user'=>$user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'user'=>auth()->user(),
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->success('successfully logged out', 200);
    }

    public function authUser()
    {
        return $this->success([
            'user'=>auth()->user(),
        ]);
    }

    public function adminSwitchToUser(Request $request)
    {
        $logged_in_user = auth()->user();
        $user = User::find($request->user_id);
        //validate Id being switched to
        if ($user == '') {
            return $this->error('user with that id was not fould', 404);
        }
        //check if the logged in user is an admin
        if ($logged_in_user->role_id != 1) {
            return $this->error('only users with a role of admin are allowed to switch', 503);
        }
        
        //store user switch history
        UserSwitch::create([
            'admin_id' => $logged_in_user->id,
            'user_id' => $user->id,
            'status' => 'active',
            'switched_at' => Carbon::now(),
        ]);
        //log out the current user
        $logged_in_user->tokens()->delete();
        
        $new_token = $user->createToken('API Token')->plainTextToken;

        return $this->success([
            'user'=>$user,
            'token' => $new_token
        ]);

        //todo
        //upon log in fetch the last active switch and mark as inactive
    }

    public function userSwitchToAdmin(Request $request)
    {
        $logged_in_user = auth()->user();
        //fetch the current active user switch
        $switch = UserSwitch::where('user_id', $logged_in_user->id)->where('status', 'active')->first();
        
        //check if there is a switch
        if ($switch == '') {
            return $this->error('you do not have an active switch. please log in again', 404);
        }
        //update the switch as inactive
        $switch->status = 'inactive';
        $switch->save();
        //fetch the admin
        $admin = User::find($switch->admin_id);
        //log out the current user
        $logged_in_user->tokens()->delete();
        //
        $new_token = $admin->createToken('API Token')->plainTextToken;

        return $this->success([
            'user'=>$admin,
            'token' => $new_token
        ]);
    }
}