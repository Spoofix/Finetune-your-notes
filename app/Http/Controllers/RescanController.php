<?php

namespace App\Http\Controllers;

use App\Console\Commands\ScanSpoofingDomains;
use App\Http\Controllers\Controller;
use App\Jobs\ScanDomains;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RescanController extends Controller
{
    public function rescan(Request $request, $domainId)
    {
        ScanDomains::dispatch([
            'domain_id'=>$domainId
        ]);

        // Additional logic if needed
        // Redirect('/');
        return redirect()->back();
    }
}

