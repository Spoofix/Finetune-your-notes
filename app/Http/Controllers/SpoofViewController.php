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
use Illuminate\Support\Facades\File;


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

        $last_batch = SpoofedDomain::where('current_scan_status', 'scanned')->where('domain_id', $activedomain)->orderBy('id', 'desc')->first();
        $list =  SpoofedDomain::validDomains()->where('current_scan_status', 'scanned')->where('domain_id', $activedomain);
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
        function containsScotiabank($haystack, $needle)
        {
            // Check if the needle string is found in the haystack
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
            // Check for subdomains
            $parts = explode('.', $haystack);
            foreach ($parts as $part) {
                // If any part of the domain contains the needle string, return true
                if (strpos($part, $needle) !== false) {
                    return true;
                }
            }
            return false;
        }
        // Filter out subdomains and elements containing "Scotiabank"
        foreach ($spoofList as $isNew) {
            $isNewValue = $isNew['spoofed_domain'];
            $firstSeen = SpoofedDomain::where('spoofed_domain', $isNewValue)->first()->created_at;
            $isNew['first_seen'] = $firstSeen;
            $domainy = Domain::where('user_id', auth()->id())->where('id', $isNew['domain_id'])->first();
            if (!containsScotiabank($isNew['spoofed_domain'], $domainy->domain_name)) {
                $modifiedIdsList[] = $isNew;
            }
        }

        //catch legits
        $domain = Domain::where('user_id', auth()->id())->where('id', $spoofData['domain_id'])->first();
        if ($spoofData['spoofDomain'] == $domain->domain_name && containsScotiabank($spoofData['spoofDomain'], $domain->domain_name)) {
            return redirect()->to($spoofData['id'] + 1);
        }

        return Inertia::render('Domain/View', [
            'isValidTakedown' => $isCompletedOrInprogress,
            'spoofList' => $modifiedIdsList,
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
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
        if ($spoofData) {
            $spoofData->progress_status = 'new';
            $spoofData->save();
        }
        $url = route('domains');
        return Inertia::location($url);
    }
    public function newDomains()
    {
        $user = Auth::user();
        $data = [];
        Mail::to($user->email)->queue(new newDomains($data));
    }
    public function getLatestScreenshots($domain)
    {
        $folderPath = base_path("./public/assets/screenshots/{$domain}");
        // Check if the folder exists
        if (!File::exists($folderPath)) {
            return response()->json(['error' => 'Folder does not exist'], 404);
        }

        // Get all files in the folder
        $files = File::files($folderPath);

        // Sort the files by their last modified time in descending order
        usort($files, function ($a, $b) {
            return File::lastModified($b) <=> File::lastModified($a);
        });

        // Extract the filenames
        $filenames = array_map(function ($file) {
            return $file->getFilename();
        }, $files);

        return response()->json(['filenames' => $filenames]);
    }
}
