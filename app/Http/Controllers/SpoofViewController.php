<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;
use App\Models\ReportformTakedownDetails;
use Illuminate\Support\Facades\Auth;

class SpoofViewController extends Controller
{
    public function spoofView($spoofId)
    {
        $user = Auth::user();
        try {
            $spoofData = SpoofedDomain::where('id', $spoofId)->first();

            if ($spoofData) {
                $userDomainTest = Domain::where('id', $spoofData->domain_id)->first();
            } else {
                return Inertia::render('DefaultPage');
            }
        } catch (\Exception $e) {
            return Inertia::render('DefaultPage');
        }
        if ($user->id !== $userDomainTest->user_id) {
            return Inertia::render('DefaultPage');
        }

        $firstSeen = SpoofedDomain::where('spoofed_domain', $spoofData->spoofed_domain)->first()->created_at;
        if (!$spoofData) {
            return;
        }
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
        return Inertia::render('Domain/View', [
            'spoofList' => $spoofList,
            'spoofData' => $spoofData,
            'userid' => auth()->id(),
            'domain' => Domain::where('user_id', auth()->id())->get(),
            'firstSeen' => $firstSeen
            // 'response' => true,
        ]);
    }
    public function requestAuthorization($spoofId)
    {
        $user = Auth::user();
        try {
            $spoofData = SpoofedDomain::where('id', $spoofId)->first();

            if ($spoofData) {
                $userDomainTest = Domain::where('id', $spoofData->domain_id)->first();
            } else {
                return Inertia::render('DefaultPage');
            }
        } catch (\Exception $e) {
            return Inertia::render('DefaultPage');
        }
        if ($user->id !== $userDomainTest->user_id) {
            return Inertia::render('DefaultPage');
        }
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        $check = ReportformTakedownDetails::where('evidence_urls', $spoofData['spoofed_domain'])->first();
        // 
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
        if ($check) {
            // return Inertia::json('Domain/View', ['response' => $check]);
            return Inertia('Domain/View', [
                'spoofList' => $spoofList,
                'spoofData' => $spoofData,
                'userid' => auth()->id(),
                'domain' => Domain::where('user_id', auth()->id())->get(),
                'response' => true,
            ]);
        }
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
    public function showMap($spoofId)
    {
        $spoofDomain = SpoofedDomain::where('id', $spoofId)->first();

        if (!$spoofDomain) {
            abort(404);
        }

        // Pass latitude and longitude to the view
        $latitude = $spoofDomain->latitude;
        $longitude = $spoofDomain->longitude;
        $spoofdomain = $spoofDomain->spoofed_domain;

        return view('map', compact('latitude', 'longitude', 'spoofdomain'));
    }
    public function screenshot($spoofId)
    {
        $spoofDomain = SpoofedDomain::where('id', $spoofId)->first();
        if (!$spoofDomain) {
            abort(404);
        }
        $name = $spoofDomain->spoofed_domain;
        $comma = ',';
        $name2 = $spoofDomain->spoofed_domain;
        $originalDir = getcwd();

        // Command
        chdir(base_path('domainsimilarity'));

        $main_file =  'keywords.txt';
        //  Log::alert($main_file);
        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);

        $current .= $name;
        file_put_contents($main_file, $current);

        $current .= $comma;
        file_put_contents($main_file, $current);

        $current .= $name2;
        file_put_contents($main_file, $current);
        $command = 'python3 screenshots.py';

        shell_exec($command);
        chdir($originalDir);
    }
}
