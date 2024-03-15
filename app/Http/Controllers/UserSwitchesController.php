<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Domain;
use App\Models\UserSwitch;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class UserSwitchesController extends Controller
{
    public function index(Request $request, $userId)
    {
        $user = Auth::user();
        $ipAddress = $request->ip();
        $uuid = Str::uuid();
        // $response = new Response('Hello World');
        // $response->cookie('uuid', $uuid, 1, '/', '127.0.0.1', false, false, false, 'None');
        $response = response('Hello World')->cookie('uuid', $uuid, 1);
        // Log::alert($response));

        if ($user->role_id === 1) {
            if (!UserSwitch::where('admin_id', $user->id)->first()) {
                DB::table('user_switches')->insert([
                    'admin_id' => $user->id,
                    'user_id' => $userId,
                    'admin_ip' => $ipAddress,
                    'switched_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $userSwitch = UserSwitch::where('admin_id', $user->id)->first();
                $userSwitch->user_id = $userId;
                $userSwitch->admin_ip = $ipAddress;
                $userSwitch->switched_at = now();
                $userSwitch->save();
            }

            $data = [
                'user' => $user,
                'isAdminSwitched' => auth()->check() && auth()->id() !== $userId,
                'domainList' => Domain::where('user_id', auth()->id())->get(),
                'organization' => Organization::all(),
                'users' => User::all()
            ];

            $userSwitch = UserSwitch::where('admin_id', auth()->id())->first();
            // auth()->onceUsingId($userSwitch->user_id);
            $newUser = User::find($userSwitch->user_id);
            Auth::login($newUser);
            Log::alert($userSwitch);

            // $userSwitch = UserSwitch::where('admin_id', auth()->id())->first();
            // auth()->onceUsingId($userSwitch->user_id);

            // return Inertia::render('Dashboard', $data);
            // return redirect()->back();
            // return response()->redirectTo(url()->previous());
            return redirect()->route('domains');
            // return response();
        }
        return redirect()->back()->with('error', 'error.');
    }
    public function back_to_admin(Request $request, $admin_id)
    {
        $ipAddress = $request->ip();
        // $uuid = $request->cookie('uuid');
        // Log::alert($uuid);

        $userSwitch = UserSwitch::where('admin_id', $admin_id)->first();
        if ($ipAddress === $userSwitch->admin_ip) {
            $newUser = User::find($userSwitch->admin_id);
            Auth::login($newUser);
            // Log::alert($userSwitch);

            DB::table('user_switches')
                ->where('admin_id', $admin_id)
                ->delete();
            // $response = new Response('Cookie deleted');
            // $response->withCookie(cookie()->forget('user_id'));
        }

        return Inertia::location(route('admin.dashboard'));
        // return Inertia::location(route('dashboard'))->toResponse(request());
    }
}
