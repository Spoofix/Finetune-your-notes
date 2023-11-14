<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Account');
    }
    public function notifications()
    {
        return Inertia::render('Settings/Notifications');
    }

    public function pricingandbilling()
    {
        return Inertia::render('Settings/PricingAndBilling');
    }
    public function policies()
    {
        return Inertia::render('Settings/Policies');
    }
    public function moniteredaccounts()
    {
        return Inertia::render('Settings/MoniteredAccounts');
    }
    public function users()
    {
        return Inertia::render('Settings/Users');
    }
}
