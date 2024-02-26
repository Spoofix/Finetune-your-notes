<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use App\Mail\newDomains;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\ReportformTakedownDetails;

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
        // $checkIfSpoofIdBelongsToUser = $spoofData->domain_id;
        $spoofDat3a = SpoofedDomain::where('id', $spoofId)->first();
        $isCompletedOrInprogress = ReportformTakedownDetails::where('evidence_urls', $spoofDat3a->spoofed_domain)->exists();
        if ($isCompletedOrInprogress) {
            $list =  SpoofedDomain::where('domain_id', $activedomain)->get();
        }
        $list = $list->filter(function ($lists) use ($isCompletedOrInprogress) {
            $reported = ReportformTakedownDetails::where('evidence_urls', $lists->spoofed_domain)->first();
            return $isCompletedOrInprogress ? $reported : !$reported;
        });
        $spoofList = array_merge($spoofList, $list->toArray());
        // Remove reals
        // function containsScotiabank($haystack, $needle)
        // {
        //     return strpos($haystack, $needle) !== false;
        // }

        // Filter the $spoofList directly to remove elements that don't contain 'scotiabank.com'
        // $spoofList = array_filter($spoofList, function ($isNew) {
        //     return containsScotiabank($isNew['spoofed_domain'], "scotiabank.com");
        // });

        // print_r($spoofList);
        return Inertia::render('Domain/View', [
            'isValidTakedown' => $isCompletedOrInprogress,
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
    public function markAsNoRisk($spoofId)
    {
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        if ($spoofData) {
            $spoofData->progress_status = 'norisk';
            $spoofData->save();
        }
        $url = route('domains');
        return Inertia::location($url);
    }
    public function markAsRisk($spoofId)
    {
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        if ($spoofData) {
            $spoofData->progress_status = 'new';
            $spoofData->save();
        }
        $url = route('domains');
        return Inertia::location($url);
    }
    public function Monitor($spoofId)
    {
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        if ($spoofData) {
            $spoofData->progress_status = 'monitoring';
            $spoofData->save();
        }
        $url = route('domains');
        return Inertia::location($url);
    }
    public function stopMonitoring($spoofId)
    {
        // $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        // if ($spoofData) {
        //     $spoofData->progress_status = 'stop_monitoring';
        //     $spoofData->save();
        // }
        $url = route('domains');
        return Inertia::location($url);
    }
    public function newDomains()
    {
        $user = Auth::user();
        $data = [];
        Mail::to($user->email)->queue(new newDomains($data));
    }
}
