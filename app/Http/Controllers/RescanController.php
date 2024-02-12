<?php

namespace App\Http\Controllers;

use App\Jobs\ScanDomains;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


class RescanController extends Controller
{
    public function rescan(Request $request, $domainId)
    {
        ScanDomains::dispatch([
            'domain_id' => $domainId
        ]);
        return redirect()->back();
    }
}
