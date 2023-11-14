<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;

class SpoofViewController extends Controller
{
    public function spoofView($spoofId)
    {
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        $activedomain = $spoofData->domain_id;
        // $spoofList = SpoofedDomain::where('domain_id', $activedomain)->first();
        $spoofList = array();

        $last_batch = SpoofedDomain::where('domain_id', $activedomain)->orderBy('id', 'desc')->first();
        $list =  SpoofedDomain::validDomains()->where('domain_id', $activedomain);
        if ($last_batch) {
            $list = $list->where('last_batch', $last_batch->last_batch);
        }
        $list = $list->get();
        $spoofList = array_merge($spoofList, $list->toArray());
        // $checkIfSpoofIdBelongsToUser = $spoofData->domain_id;
        // if ($spoofId === ) { 
        return Inertia::render('Domain/View', [
            'spoofList' => $spoofList,
            'spoofData' => $spoofData,
            'userid' => auth()->id(),
            'domain' => Domain::where('user_id', auth()->id())->get()
        ]);
        // }
    }
    public function requestAuthorization($spoofId)
    {
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        $activedomain = $spoofData->domain_id;
        // $spoofList = SpoofedDomain::where('domain_id', $activedomain)->first();
        $spoofList = array();

        $last_batch = SpoofedDomain::where('domain_id', $activedomain)->orderBy('id', 'desc')->first();
        $list =  SpoofedDomain::validDomains()->where('domain_id', $activedomain);
        if ($last_batch) {
            $list = $list->where('last_batch', $last_batch->last_batch);
        }
        $list = $list->get();
        $spoofList = array_merge($spoofList, $list->toArray());
        // $checkIfSpoofIdBelongsToUser = $spoofData->domain_id;
        // if ($spoofId === ) { requestAuthorization
        return Inertia::render('Domain/RequestAuthorization', [
            'spoofList' => $spoofList,
            'spoofData' => $spoofData,
            'userid' => auth()->id(),
            'domain' => Domain::where('user_id', auth()->id())->get()
        ]);
        // }
    }

    public function spoofReport($spoofId)
    {

        $spoofData = SpoofedDomain::where('id', $spoofId)->first();

        return Inertia::render('Domain/Report', [
            'spoofData' => $spoofData,
            'initValueHere' => $this->htmlFromat($spoofData)
        ]);
    }

    private function htmlFromat($spoofData)
    {
        return view('report_format', compact('spoofData'))->render();
    }
}
