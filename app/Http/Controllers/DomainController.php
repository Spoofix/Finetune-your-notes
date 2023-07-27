<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use App\Models\OrgDomain;
use App\Services\WhoIsSearch;
use App\Models\DomainDetail;
use App\Models\Organization;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        // $data = DomainDetail::where('domain_id', request('domain_id'))->first();
                
        return Inertia::render('Domain/View', [
           
            ]);
    }
     
    public function detail(Request $request)
    {
 
    }
    }
