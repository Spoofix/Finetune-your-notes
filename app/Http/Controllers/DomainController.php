<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Inertia\Controller;
use App\Jobs\ScanDomains;
use App\Models\OrgDomain;
use App\Models\DomainDetail;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Services\WhoIsSearch;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        // $data = DomainDetail::where('domain_id', request('domain_id'))->first();

        // return Inertia::render('Domain/View', [

        //     ]);ReportForm 
    }
    // ReportForm
    public function ReportForm(Request $request)
    {
        return Inertia::render('ReportForm', []);
    }
    // Messages
    public function Messages(Request $request)
    {
        return Inertia::render('Messages', []);
    }

    public function store(Request $request)
    {
        $request->validate([
            'domains' => 'required|string'
        ]);

        $domains = explode(",", $request->domains);
        foreach ($domains as $domain) {
            $created =  Domain::create([
                'domain_name' => trim($domain),
                'user_id' => auth()->id(),
            ]);

            ScanDomains::dispatch([
                'domain_id' => $created->id
            ]);
        }
    }
}
