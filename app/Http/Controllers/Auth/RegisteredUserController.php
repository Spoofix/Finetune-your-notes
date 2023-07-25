<?php

namespace App\Http\Controllers\Auth;

use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Domain;
use App\Jobs\ScanDomains;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
// use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rules\Password;
use App\Notifications\WelcomeNotification;
// use App\Console\Commands\ScanSpoofingDomains;




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

        

        $domains = explode(",",$request->domains);
        foreach ($domains as $domain){
            Domain::create([
                'domain_name'=>trim($domain),
                'user_id'=>$user->id
            ]);
        }

        ScanDomains::dispatch([
            'user_id'=>$user->id
        ]);


        event(new Registered($user));
        Auth::login($user);
        $user->notify(new WelcomeNotification());

        Alert::success('SuccessAlert','Registration succesful, we are scanning the domains that you provided to identify possible spoof domains');
    

        return redirect(RouteServiceProvider::HOME);
    }
}
