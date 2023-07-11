<?php

namespace App\Http\Controllers\Auth;

use App\Console\Commands\ScanSpoofingDomains;
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\User;
use App\Notifications\WelcomeNotification;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'provinces' => config('provinces'),
            'timezones' => config('timezones'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'domains' => 'required|string',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'organization' => $request->organization,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $domains = explode(",",$request->domain);
        foreach ($domains as $domain){
            Domain::create([
                'domain_name'=>$domain,
                'user_id'=>$user->id
            ]);
        }

        Artisan::call(ScanSpoofingDomains::class,['--user_id'=>$user->id]);


        event(new Registered($user));
        Auth::login($user);
        $user->notify(new WelcomeNotification());

        return redirect(RouteServiceProvider::HOME);
    }
}
