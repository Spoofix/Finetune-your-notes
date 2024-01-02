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
        foreach ($DomainDetail as $Id) {
            // if (is_array($Id)) {
            $domainId = $Id['id'];
            // }
            $last_batch = SpoofedDomain::where('domain_id', $domainId)->orderBy('id', 'desc')->first();
            $list =  SpoofedDomain::validDomains()->where('domain_id', $domainId);
            if ($last_batch) {
                $list = $list->where('last_batch', $last_batch->last_batch);
            }
            $list = $list->get();
            $IdsList = array_merge($IdsList, $list->toArray());
        }
        return Inertia::render('Domains', [
            'spoofList' => $IdsList,
            'domainList' => Domain::where('user_id', auth()->id())->get()
        ]);
    }
    // InProgress
    public function InProgress()
    {
        $DomainDetail = Domain::where('user_id', auth()->id())->get();
        $IdsList = array();
        $reportsDetails = array();
        foreach ($DomainDetail as $Id) {
            // if (is_array($Id)) {
            $domainId = $Id['id'];
            // }
            // $last_batch = SpoofedDomain::where('domain_id', $domainId)->orderBy('id', 'desc')->first();
            $list =  SpoofedDomain::validDomains()->where('domain_id', $domainId);
            // if ($last_batch) {
            //     $list = $list->where('last_batch', $last_batch->last_batch);
            $list = $list->where('spoof_status_new', 'Inprogress');
            $list = $list->get();
            $reportDetails = array();
            foreach ($list as $spoofDetail) {
                // echo ($spoofDetail);
                // Log::alert($spoofDetail);
                $reported = ReportformTakedownDetails::where('evidence_urls', $spoofDetail->spoofed_domain)->first();
                // Log::alert($reported);
                if ($spoofDetail && $reported) {
                    $spoofDetails = array_merge($spoofDetail->toArray(), $reported->toArray());
                }
                $user = User::where('id', $spoofDetails['reported_by_user_id'])->first();
                // $user = $user->name;
                // log::alert($user);
                // $reported = array_pop($reported->id);
                if ($spoofDetail && $reported) {
                    $spoofDetails = array_merge($reported->toArray(), $spoofDetail->toArray(), ['user_name' => $user->name]);
                }

                // Log::alert($spoofDetails);
                if ($spoofDetails !== [] || $spoofDetails !== null) {
                    $appeniding = array_push($reportDetails, $spoofDetails);
                    // log::alert($spoofDetails);
                }
                // $reportDetails = array_merge($reportDetails, $spoofDetails);
                // log::alert($spoofDetails);
                // echo ($reportDetails);
            }
            // }
            // Log::alert($reportDetails);
            log::alert('another round');
            $reportsDetails = array_merge($reportsDetails, $reportDetails);
            // $reportsDetails = array_reverse($reportsDetails);
            $IdsList = array_merge($IdsList, $list->toArray());
        }
        log::alert($reportsDetails);
        // // $flags = SORT_REGULAR;
        // foreach ($reportsDetails as $reportsDetail) {
        //     $reportsDetail = print_r($reportsDetail, true);

        // }

        $uniqueReportsDetails = array_map("unserialize", array_unique(array_map("serialize", $reportsDetails)));
        $reportsDetails = array_reverse($uniqueReportsDetails);

        return Inertia::render('InProgress', [
            'spoofList' => $reportsDetails,
            'domainList' => Domain::where('user_id', auth()->id())->get(),
            // 'reportDetails' => $reportsDetails

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
