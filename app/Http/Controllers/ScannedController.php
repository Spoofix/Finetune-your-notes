<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Domain;
use App\Models\DomainDetail;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\ReportformTakedownDetails;
use Illuminate\Support\Facades\Auth;
use LengthException;

class ScannedController extends Controller
{
    // Domains
    public function index()
    {
        $DomainDetail = Domain::where('user_id', auth()->id())->get();
        $IdsList = array();
        $modifiedIdsList = array();
        foreach ($DomainDetail as $Id) {
            // if (is_array($Id)) {
            $domainId = $Id['id'];
            // };
            $last_batch = SpoofedDomain::where('current_scan_status', 'scanned')->where('domain_id', $domainId)->orderBy('id', 'desc')->first();
            $list =  SpoofedDomain::validDomains()->where('domain_id', $domainId)->where('current_scan_status', 'scanned');
            if ($last_batch) {
                $list = $list->where('last_batch', $last_batch->last_batch);
            }
            $list = $list->get();
            $list = $list->reject(function ($lists) {
                $reported = ReportformTakedownDetails::where('evidence_urls', $lists->spoofed_domain)->first();
                return $reported;
            });
            $list = $list->reject(function ($lists) {
                $myDomain = Domain::where('domain_name', $lists->spoofed_domain)->first();
                return $myDomain;
            });
            $list = $list->reject(function ($domain) {
                return $domain->progress_status === 'norisk';
            });
            $IdsList = array_merge($IdsList, $list->toArray());
        }

        //Remove reals
        // function containsScotiabank($haystack, $needle)
        // {
        //     return strpos($haystack, $needle) !== false;
        // }

        // foreach ($IdsList as $isNew) {
        //     $isNewValue = $isNew['spoofed_domain'];
        //     $firstSeen = SpoofedDomain::where('spoofed_domain', $isNewValue)->first()->created_at;
        //     $isNew['first_seen'] = $firstSeen;
        //     $domainy = Domain::where('user_id', auth()->id())->where('id', $isNew['domain_id'])->first();
        //     if (!containsScotiabank($isNew['spoofed_domain'], $domainy->domain_name)) {
        //         $modifiedIdsList[] = $isNew;
        //     }
        // }
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
        foreach ($IdsList as $isNew) {
            $isNewValue = $isNew['spoofed_domain'];
            $firstSeen = SpoofedDomain::where('spoofed_domain', $isNewValue)->first()->created_at;
            $isNew['first_seen'] = $firstSeen;
            $domainy = Domain::where('user_id', auth()->id())->where('id', $isNew['domain_id'])->first();
            if (!containsScotiabank($isNew['spoofed_domain'], $domainy->domain_name)) {
                $modifiedIdsList[] = $isNew;
            }
        }

        return Inertia::render('Domains', [
            'spoofList' => $modifiedIdsList,
            'domainList' => Domain::where('user_id', auth()->id())->get()
        ]);
    }
    // InProgress method
    public function InProgress()
    {
        $DomainDetail = Domain::where('user_id', auth()->id())->get();
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



        return Inertia::render('InProgress', [
            'spoofList' => $uniqueReportDetails,
            'domainList' => Domain::where('user_id', auth()->id())->get(),
        ]);
    }
    // Completed
    public function Completed()
    {
        $DomainDetail = Domain::where('user_id', auth()->id())->get();
        $IdsList = array();
        foreach ($DomainDetail as $Id) {
            // if (is_array($Id)) {
            $domainId = $Id['id'];
            // }
            $last_batch = SpoofedDomain::where('domain_id', $domainId)->orderBy('id', 'desc')->first();
            $list =  SpoofedDomain::validDomains()->where('domain_id', $domainId);
            // if ($last_batch) {
            //     $list = $list->where('last_batch', $last_batch->last_batch);
            // }
            $list = $list->where('spoof_status_new', 'Completed');
            $list = $list->get();
            $IdsList = array_merge($IdsList, $list->toArray());
        }
        return Inertia::render('Completed', [
            'spoofList' => $IdsList,
            'domainList' => Domain::where('user_id', auth()->id())->get()
        ]);
    }
}
