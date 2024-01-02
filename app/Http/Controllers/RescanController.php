<?php

namespace App\Http\Controllers;

// use alert;
use App\Jobs\ScanDomains;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
// use App\Console\Commands\ScanSpoofingDomains;



class RescanController extends Controller
{
    public function rescan(Request $request, $domainId)
    {
        ScanDomains::dispatch([
            'domain_id' => $domainId
        ]);

        //    Alert::success('Registration succesful', ' we are scanning the domains that you provided to identify possible spoof domains');

        // Additional logic if needed
        // Redirect('/');
        return redirect()->back();
    }
}
