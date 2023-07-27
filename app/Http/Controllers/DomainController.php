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
                
        return Inertia::render('Domain/View', [
           
            ]);
    }
     
    public function store(Request $request)
    {
        $request->validate([
            'domains' => 'required|string'
        ]);
        
        $domains = explode(",",$request->domains);
        foreach ($domains as $domain){
            Domain::create([
                'domain_name'=>trim($domain),
                'user_id'=>$user_id,
            ]);
        }

        ScanDomains::dispatch([
            'domain_id'=> $domains->id
        ]);
    }
    }
