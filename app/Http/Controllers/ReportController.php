<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Domain;
use App\Models\Reporting;
use App\Mail\ReportingMail;
use App\Models\SpoofedDomain;
use App\Models\ReportedSpoofDomains;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\CountriesCybersecurity;
use App\Models\ReportformTakedownDetails;

class ReportController extends Controller
{
    // public function index()
    // {
    //     return Inertia::render('Admin/Index', []);
    // }
    public function Report()
    {
        $DomainDetail = Domain::all();
        $uniqueReportDetails = [];

        foreach ($DomainDetail as $domain) {
            $list = SpoofedDomain::where('domain_id', $domain->id)->get();

            foreach ($list as $spoofDetail) {
                $reported = ReportformTakedownDetails::where('evidence_urls', $spoofDetail->spoofed_domain)->first();

                if ($spoofDetail && $reported) {
                    $spoofDetailArray = $spoofDetail->toArray();
                    $spoofDetailArray['id2'] = $spoofDetailArray['id'];
                    unset($spoofDetailArray['id']);

                    $spoofDetails = array_merge($spoofDetailArray, $reported->toArray());

                    $user = User::find($spoofDetails['reported_by_user_id']);
                    $Reported_to = ReportedSpoofDomains::where('spoof_id', $spoofDetail->id)->get();


                    if ($Reported_to) {
                        $count = $Reported_to->count();
                        $emailsArray = explode('","', $spoofDetail->emails);

                        $overCount = 21 + count($emailsArray);
                        $spoofDetails['Reported_to_value'] = $overCount;
                        $spoofDetails['Reported_to'] = $count;
                    }
                    if ($user) {
                        $spoofDetails['user_name'] = $user->name;
                        $uniqueReportDetails[$spoofDetail->spoofed_domain] = $spoofDetails;
                    }
                }
            }
        }

        usort($uniqueReportDetails, function ($a, $b) {
            return $b['id'] - $a['id'];
        });

        $uniqueReportDetails = array_values($uniqueReportDetails);



        return Inertia::render('Admin/Reporting', [
            'spoofList' => $uniqueReportDetails,
            'domainList' => Domain::all(),
        ]);
    }
    private function htmlFromat($spoofData)
    {
        return view('report_format', compact('spoofData'))->render();
    }
    public function spoofReport($spoofId)
    {
        $user = Auth::user();
        $spoofData = SpoofedDomain::where('id', $spoofId)->first();
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

        foreach ($spoofList as $isNew) {
            $isNewValue = $isNew['spoofed_domain'];
            $firstSeen = SpoofedDomain::where('spoofed_domain', $isNewValue)->first()->created_at;
            $isNew['first_seen'] = $firstSeen;
            $domainy = Domain::where('id', $isNew['domain_id'])->first();
            // if (!containsScotiabank($isNew['spoofed_domain'], $domainy->domain_name)) {
            $modifiedIdsList[] = $isNew;
            // }
        }


        //catch legits
        // $domain = Domain::where('id', $spoofData['domain_id'])->first();
        // if ($spoofData['spoofDomain'] == $domain->domain_name && containsScotiabank($spoofData['spoofDomain'], $domain->domain_name)) {
        //     return redirect()->to($spoofData['id'] + 1);
        // }


        //reporting info
        $user = Auth::user();
        $domain = Domain::where('id', $spoofData['domain_id'])->first();
        $ReportingData = Reporting::all();
        $reportingCountry = CountriesCybersecurity::where("country", "LIKE", "%" . $spoofData->server_country . "%")->first(); // Fetch the result using first() or get()
        if ($reportingCountry) {
            $ReportingData[] = [
                'name' => $reportingCountry->country,
                'form_url' => $reportingCountry->url,
                'email' => $reportingCountry->email,
                'Can_Report' => 'true',
            ];
        }
        $abuseEmails = $spoofData->emails;
        if ($abuseEmails && strpos($abuseEmails, '[') === 0) {
            $abuseEmailsArray = json_decode($abuseEmails);
            $abuseEmailsArray =  array_unique($abuseEmailsArray);
            foreach ($abuseEmailsArray as $abuseEmailsArrays) {
                if ($abuseEmailsArrays) {
                    $parts = explode('@', $abuseEmailsArrays);
                    $domain1 = $parts[1];
                    $domainWithoutTld = explode('.', $domain1)[0];
                    $ReportingData[] = [
                        'name' => ucfirst($domainWithoutTld . " " . "Registrar"),
                        'form_url' => "",
                        'email' => $abuseEmailsArrays,
                        'Can_Report' => 'true',
                    ];
                }
            }
        } elseif ($abuseEmails && strpos($abuseEmails, '[') !== 0) {
            $abuseEmailsArray = [$abuseEmails];
            if ($abuseEmailsArray) {
                $parts = explode('@', $abuseEmailsArray[0]);
                $domain1 = $parts[1];
                $domainWithoutTld = explode('.', $domain1)[0];
                $ReportingData[] = [
                    'name' => ucfirst($domainWithoutTld . " " . " Registrar"),
                    'form_url' => "",
                    'email' => $abuseEmailsArray,
                    'Can_Report' => 'true',
                ];
            }
        }
        $ispAbuseEmails = $spoofData->ISP_Abuse;
        $abuseEmailsArray = [$ispAbuseEmails];
        if ($abuseEmailsArray && $ispAbuseEmails) {
            $parts = explode('@', $abuseEmailsArray[0]);
            $domain1 = $parts[1];
            $domainWithoutTld = explode('.', $domain1)[0];
            $ReportingData[] = [
                'name' => ucfirst($domainWithoutTld . " " . " ISP"),
                'form_url' => "",
                'email' => $abuseEmailsArray,
                'Can_Report' => 'true',
            ];
        }
        // getting reported 
        $reported = ReportedSpoofDomains::where('spoof_id', $spoofData->id)->get();
        foreach ($ReportingData as $ReportingDatas) {
            foreach ($reported as $reporteds) {
                if ($ReportingDatas['name'] === $reporteds['reportingName'] && $reporteds['reported_via'] === 'form') {
                    $ReportingDatas['spoof_id'] = $reporteds['spoof_id'];
                    $ReportingDatas['admin_id'] = $reporteds['admin_id'];
                    $ReportingDatas['reported_via'] = $reporteds['reported_via'] === 'form' ? 'form' : 'email_holder';
                    $ReportingDatas['email2'] = strlen($reporteds['reported_via']) > 6  ? $reporteds['reported_via'] : 'form';
                    $ReportingDatas['reportingName'] = $reporteds['reportingName'];
                }
                if ($ReportingDatas['name'] === $reporteds['reportingName'] && $reporteds['reported_via'] !== 'form') {
                    $ReportingDatas['reported_via2'] = 'email';
                    $ReportingDatas['spoof_id'] = $reporteds['spoof_id'];
                    $ReportingDatas['admin_id'] = $reporteds['admin_id'];
                    $ReportingDatas['email2'] = $reporteds['reported_via'];
                    $ReportingDatas['reportingName'] = $reporteds['reportingName'];
                }
            }
        }

        return Inertia::render('Admin/Report', [
            'report_to' => $ReportingData,
            // 'spoofData' => $spoofData,
            'initValueHere' => $this->htmlFromat($spoofData),
            // spoof view
            'isValidTakedown' => $isCompletedOrInprogress,
            'spoofList' => $modifiedIdsList,
            'spoofData' => $spoofData,
            'userid' => $user->role_id,
            'userName' => $user->name,
            'domain' => $domain,
            'firstSeen' => $firstSeen
            // 'response' => true,
        ]);
    }
    public function reportSpoofView($spoofId)
    {
        $user = Auth::user();

        try {
            $spoofData = SpoofedDomain::where('id', $spoofId)->first();
            $Reported_to = ReportedSpoofDomains::where('spoof_id', $spoofData->id)->get();


            if ($Reported_to) {
                $count = $Reported_to->count();
                $emailsArray = explode('","', $spoofData->emails);

                $overCount = 21 + count($emailsArray);
                $spoofData['Reported_to_value'] = $overCount;
                $spoofData['Reported_to'] = $count;
            }
            if ($spoofData) {
                $userDomainTest = Domain::where('id', $spoofData->domain_id)->first();
            } else {
                return Inertia::render('DefaultPage');
            }
        } catch (\Exception $e) {
            return Inertia::render('DefaultPage');
        }
        if ($user->role_id !== 1) {
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
            $domainy = Domain::where('id', $isNew['domain_id'])->first();
            if (!containsScotiabank($isNew['spoofed_domain'], $domainy->domain_name)) {
                $modifiedIdsList[] = $isNew;
            }
        }

        //catch legits
        $domain = Domain::where('id', $spoofData['domain_id'])->first();
        if ($spoofData['spoofDomain'] == $domain->domain_name && containsScotiabank($spoofData['spoofDomain'], $domain->domain_name)) {
            return redirect()->to($spoofData['id'] + 1);
        }

        return Inertia::render('Admin/View', [
            'isValidTakedown' => $isCompletedOrInprogress,
            'spoofList' => $modifiedIdsList,
            'spoofData' => $spoofData,
            'userid' => $user->role_id,
            'domain' => Domain::where('id', $spoofData['domain_id'])->first(),
            'firstSeen' => $firstSeen
            // 'response' => true,
        ]);
    }
    public function sendReport($spoofId, $email, $reportingName)
    {
        Mail::to($email)->send(new ReportingMail($email, $spoofId));
        // save data
        ReportedSpoofDomains::insert([
            'spoof_id' => $spoofId,
            'reportingName' => $reportingName ?  $reportingName : null,
            'admin_id' =>  Auth::user()->id,
            'reported_via' => $email
        ]);
    }
    public function formReport($spoofId, $reportingName)
    {
        // save data
        ReportedSpoofDomains::insert([
            'spoof_id' => $spoofId,
            'reportingName' => $reportingName ?  $reportingName : null,
            'admin_id' =>  Auth::user()->id,
            'reported_via' => 'form'
        ]);
    }
}
