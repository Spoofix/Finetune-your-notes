<?php

namespace App\Http\Controllers;

use App\Services\DnsTwist;
use App\Services\OpenSquat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'domain' => 'required',
        ]);
        $domain = OpenSquat::search($request->domain) ;//&& DnsTwist::search($request->domain)


        // dd($domain);

        return Inertia::render('Search', [
            'domain' => $domain,
        ]);
    }
}
