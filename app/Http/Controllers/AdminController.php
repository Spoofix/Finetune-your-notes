<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Domain;
use App\Models\Organization;
use App\Models\SpoofedDomain;
use Illuminate\Support\Facades\Auth;
use App\Models\ReportformTakedownDetails;

class AdminController extends Controller
{
    // dashboard
    public function index()
    {
        return Inertia::render('Admin/Pages/Dashboard', [
            'domainList' => Domain::where('user_id', auth()->id())->get(),
            'organization' => Organization::all(),
            'users' => User::all()
        ]);
    }
    // scanned
    public function scanned()
    {
    }
    // completed
    public function completed()
    {
    }
    // OrgUsers
    public function Org_Users()
    {
        return Inertia::render('Admin/Pages/Org&Users', [
            'domainList' => Domain::where('user_id', auth()->id())->get(),
            'organization' => Organization::all(),
            'users' => User::all()
        ]);
    }
    // messages
    public function messages()
    {
    }
}
