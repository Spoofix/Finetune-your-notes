<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Domain;
use App\Jobs\ScanDomains;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Artisan;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rules\Password;
use App\Notifications\WelcomeNotification;
use App\Console\Commands\ScanSpoofingDomains;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            // 'provinces' => config('provinces'),
            // 'timezones' => config('timezones'),
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
        ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'organization' => $request->organization,
        //     'phone_number' => $request->phone_number,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // $emails = user::where('email', $request->email)->get();
        // foreach ($emails as $email) {
        //     if ($request->email === $email->email) {
        //         log::alert('cant repeat that again');
        //         return;
        //     }
        // }

        // if ($request->user_role == 'User') {
        //     $user_id = 2;
        // } else {
        //     $user_id = 1;
        // }
        // // mail::to($request->email)->queue($user);
        // log::alert($request);

        $org = Organization::create([
            'name' => $request->organization,
            'user_id' => -1,
            // 'last_search' => time(),
            'status' => 'ACTIVE',
            'email' => $request->email,
            'phone' => $request->phone_number,
            'terms_and_conditions' => '',
            'plan_id' => 1

        ]);
        $org_id = Organization::where('name', $request->organization)->first();
        $first_name = explode(' ', $request->name)[0];
        $second_name = explode(' ', $request->name)[1];
        $user = User::create([
            'name' => $first_name,
            'organization' => $org_id->id,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'org_id' => $org_id->id,
            'role_id' => 2,
            'org_role_id' => 1,
            'status' => 'ACTIVE',
            'profile' => '',
            'second_name' => $second_name,
            'password' => Hash::make($request->password),
        ]);
        $org_id->user_id = User::where('email', $request->email)->first()->id;
        $org_id->save();
        // // log::alert($user);
        $new_user_id = user::where('email', $request->email)->first();
        $new_user_id = $new_user_id->id;
        $notificationSettings = Notifications::create([
            'user_id' => $new_user_id,
            'all_notifications' => 'true',
            'take_down_request' => 'true',
            'takedown_completed' => 'true',
            'news_and_updates' => 'true',
            'reminders' => 'true'
        ]);

        $domains = explode(",", $request->domains);
        foreach ($domains as $domain) {
            Domain::create([
                'domain_name' => trim($domain),
                'user_id' => $new_user_id,
                'org_id' => $org_id->id,
            ]);
        }

        ScanDomains::dispatch([
            'user_id' => $user->id
        ]);


        event(new Registered($user));
        Auth::login($user);
        $user->notify(new WelcomeNotification());

        //Alert::success('SuccessAlert','Registration succesful, we are scanning the domains that you provided to identify possible spoof domains');
        return redirect(RouteServiceProvider::HOME);
    }
}
