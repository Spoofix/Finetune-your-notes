<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Domain;
use App\Mail\ResetPassword;
use App\Models\PaymentPlan;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\WelcomeNotification;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Request as FacadesRequest;

class SettingsController extends Controller
{
    public function index(Request $request): Response
    {
        $auth_user = Auth::user();
        $org = Organization::where('id', $auth_user->org_id)->first();
        return Inertia::render('Settings/Account', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'auth_user' => auth()->user(),
            'org' => $org,
        ]);
    }
    // public function sendResetPasswordEmail()
    // {
    //     $email = new ResetPassword();

    //     Mail::to('recipient@example.com')->send($email);

    //     // return 'Reset password email sent successfully!';
    // }
    public function sendResetPasswordEmail()
    {
        $user = Auth::user();

        Mail::to($user->email)->queue(new ResetPassword($user));

        return redirect()->back()->with('status', 'Password reset email sent successfully!');
    }
    public function notifications()
    {
        $notificationSettings = Notifications::where('user_id', auth()->id())->first();
        return Inertia::render('Settings/Notifications', [
            'notificationSettings' => $notificationSettings,
        ]);
    }

    public function pricingandbilling()
    {
        $auth_user = Auth::user();
        $org_id = Organization::where('id', $auth_user->org_id)->first();
        $plan = PaymentPlan::where('id', $org_id->plan_id)->first();
        return Inertia::render('Settings/PricingAndBilling', [
            'plan' => $plan,
        ]);
    }
    public function policies()
    {
        $auth_user = Auth::user();
        $organization = Organization::where('id', $auth_user->org_id)->first();
        $terms_and_conditions = $organization->terms_and_conditions;
        if ($terms_and_conditions) {
            $terms_and_conditions = trim($terms_and_conditions, "[]");
            $terms_and_conditions = str_replace('"', '', $terms_and_conditions);

            $array = explode(',', $terms_and_conditions);
            $array = array_map('trim', $array);
            $id = $array[1];
            // dd($array);
            $cleanedString = str_replace("'", '', $id);
            $id = intval($cleanedString);
            // dd($id);
            $theUser = User::where('id', $id)->first();
            $theUser = [$theUser->name, $theUser->second_name];
            // dd($theUser->name);
            return Inertia::render('Settings/Policies', [
                'organization' => $organization,
                'theUser' => $theUser
            ]);
        }
        return Inertia::render('Settings/Policies', [
            'organization' => $organization,
            'theUser' =>  ['', '']
        ]);
    }
    public function terms_and_conditions(Request $request)
    {
        $auth_user = Auth::user()->id;
        $currentDate = date('jS F Y');
        // echo $currentDate;
        $Accepted_or_declined  = $request->Accepted_or_declined;
        $dataArray = [$Accepted_or_declined, $auth_user, $currentDate];

        // Encode the array to JSON
        $jsonString = json_encode($dataArray);

        // Assuming 'Organization' is your model class
        $yourModel = Organization::where('id', Auth::user()->org_id)->first();

        $yourModel->terms_and_conditions = $jsonString;
        $yourModel->save();
        return self::policies();
    }
    public function moniteredaccounts()
    {
        $auth_user = Auth::user();
        $org_id = Organization::where('id', $auth_user->org_id)->first();
        $OrgDomains = Domain::where('org_id', $org_id->id)->get();
        return Inertia::render('Settings/MoniteredAccounts', [
            'OrgDomains' => $OrgDomains,
        ]);
    }
    public function users()
    {
        $auth_user = Auth::user();
        $users = User::where('org_id', $auth_user->org_id)->get();
        return Inertia::render('Settings/Users', [
            'users' => $users,
            // 'page' => 'users',
        ]);
    }
    public function addUser(Request $request)
    {
        $auth_user = Auth::user();

        // $request->validate([
        //     //     'name' => 'required|string|max:255',
        //     //     'second_name' => 'required|string|max:255',
        //     //     'phone_number' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:' . User::class,
        //     //     'user_role' => 'required|string|max:255',

        //     //     // 'password' => ['required', 'confirmed', Password::defaults()],
        // ]);
        $emails = user::where('email', $request->email)->get();
        foreach ($emails as $email) {
            if ($request->email === $email->email) {
                log::alert('cant repeat that again');
                return;
            }
        }

        if ($request->user_role == 'User') {
            $user_id = 2;
        } else {
            $user_id = 1;
        }
        // mail::to($request->email)->queue($user);
        log::alert($request);
        $org_id = $auth_user->org_id;
        $user = User::create([
            'name' => $request->name,
            'organization' => '',
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'org_id' => $org_id,
            'role_id' => 1,
            'org_role_id' => $user_id,
            'status' => 'ACTIVE',
            'profile' => '',
            'second_name' => $request->second_name,
            'password' => Hash::make('reset'),
            // 'password' => Hash::make($request->password), 
        ]);
        // log::alert($user);
        $new_user_id = user::where('email', $request->email)->first();
        $new_user_id = $new_user_id->id;
        $notificationSettings = notifications::create([
            'user_id' => $new_user_id,
            'all_notifications' => 'true',
            'take_down_request' => 'true',
            'takedown_completed' => 'true',
            'news_and_updates' => 'true',
            'reminders' => 'true'
        ]);
        log::alert($notificationSettings);
        event(new Registered($user));
        Auth::login($user);
        $user->notify(new WelcomeNotification());
    }
    public function notifications_update(Request $request)
    {
        log::alert($request);
        $auth_user = Auth::user();
        $notificationSettings = notifications::where('user_id', $auth_user->id)->first();
        $notificationSettings->all_notifications = $request->all_notifications;
        $notificationSettings->takedown_requests = $request->takedown_requests;
        $notificationSettings->takedown_completed = $request->takedown_completed;
        $notificationSettings->news_and_updates = $request->news_and_updates;
        $notificationSettings->reminders = $request->reminders;
        $notificationSettings->save();
        log::alert($notificationSettings);
        // return Redirect::route('');
        // return Inertia::render(
        //     '/settings/notifications',
        //     []
        // );

    }
    // ;;
    // public function index2()
    // {
    //     $auth_user = Auth::user();
    //     $users = User::whereNot('email', $auth_user->email)->where('role_id', '!=', 1)->get();
    //     return Inertia::render('Settings/Users', [
    //         'users' => $users,
    //         'page' => 'users',
    //     ]);
    // }

    // public function pending()
    // {
    //     $auth_user = Auth::user();
    //     $users = User::whereNot('email', $auth_user->email)->where('role_id', '!=', 1)->where('status', '!=', 'ACTIVE')->get();
    //     return Inertia::render('Settings/Users', [
    //         'users' => $users,
    //         'page' => 'pending',
    //     ]);
    // }
}
