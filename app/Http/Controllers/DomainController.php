<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Inertia\Controller;
use App\Jobs\ScanDomains;
use App\Jobs\WhoIsRating;
use App\Models\OrgDomain;
use App\Models\DomainDetail;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;
use App\Services\WhoIsSearch;
use App\Jobs\ScanDomainRatings;
use Illuminate\Http\RedirectResponse;
use App\Jobs\LocationSslAndRedirectsJob;
use Illuminate\Support\Facades\Redirect;
use App\Models\ReportformTakedownDetails;
use App\Http\Requests\UserReportSpoofingSite;
use App\Models\Messages;
use Inertia\Response;

class DomainController extends Controller
{
    public function index(Request $request)
    {
        // $data = DomainDetail::where('domain_id', request('domain_id'))->first();

        // return Inertia::render('Domain/View', [

        //     ]);ReportForm 
    }
    // ReportForm
    public function ReportForm(Request $request)
    {
        $domainList = Domain::where('user_id', auth()->id())->get();
        return Inertia::render('ReportForm', [
            'domainList' => $domainList
        ]);
    }

    public function ReportSpoofySite(Request $request): RedirectResponse //UserReportSpoofingSite
    {
        $request->validate([
            'abuse_type' => 'required|in:Spoofing,Phishing',
            'evidence_urls' => 'required|unique:reportform_takedown_details,evidence_urls',
            'targetDomain' => 'required',
            'Confirm' => 'required',
            'additional_notes' => 'nullable|string|max:255',
            'observed_date' => 'nullable|date',
            'carbon_copy_request_to' => 'nullable|email',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $fileName = '';
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            if ($attachment->isValid()) {
                $fileType = $attachment->getClientOriginalExtension();
                $fileName = $request->evidence_urls . '.' . $fileType;
                $path = $attachment->storeAs('public/assets/attachments', $fileName);
            }
        }


        ReportformTakedownDetails::create([
            'abuse_type' => $request->abuse_type,
            'evidence_urls' => $request->evidence_urls,
            'additional_notes' => $request->additional_notes ? $request->additional_notes : '',
            'observed_date' => $request->observed_date ?  $request->observed_date : '',
            'attachments' => $fileName ? $fileName : '',
            'carbon_copy_request_to' => $request->carbon_copy_request_to ? $request->carbon_copy_request_to : '',
            'reported_via' => $request->reported_via,
            'reported_by_user_id' => auth()->id(),
        ]);

        $target_domain = Domain::where('domain_name', $request->targetDomain);

        $spoofDomain = $request->evidence_urls;
        if ($request->reported_via !== 'report_form') {
            $domain_id = $request->reported_via;
            $spoofDomain = SpoofedDomain::where('id', $request->id)->first();
            $spoofDomain->spoof_status_new = 'inprogress';
            $spoofDomain->save();
            return Redirect::route('spoof.view', ['spoofId' => $request->id]);
            // return Redirect::route('InProgress');
        } else {
            $domain_id = $target_domain->first()->id;
            try {
                $last_batch = SpoofedDomain::where('domain_id', $target_domain->first()->id)->latest()->firstOrFail();
                if ($last_batch) {
                    $last_batch = $last_batch->last_batch;
                }
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $last_batch = time();
            }

            $created = SpoofedDomain::create([
                'domain_id' => $domain_id,
                'spoofed_domain' => $spoofDomain,
                'csscolor' => 'processing',
                'domain_rating' => 'processing',
                'domainsimilarityrate' => 'processing',
                'htmls' => 'processing',
                'phashes' => 'processing',
                'last_batch' => $last_batch,
                'ip_address' => 'processing',
                'server_city' => 'processing',
                'region' => 'processing',
                'server_country' => 'processing',
                'latitude' => '0.0000',
                'longitude' => '0.0000',
                'organization' => 'processing',
                'isp' => 'processing',
                'server_os' => 'processing',
                'ssl_certificate_details' => 'processing',
                'redirect_urls' => 'processing',
                'http_status_code' => 'processing',
                'cookies' => 'processing',
                'console_messages' => 'processing',
                'security_headers' => 'processing',
                'cookies' => 'processing',
                'console_messages' => 'processing',
                'spoof_status_new' => 'inprogress'
            ]);

            ScanDomainRatings::dispatch($created);
            LocationSslAndRedirectsJob::dispatch($created);
            WhoIsRating::dispatch($created);



            $spoofId = SpoofedDomain::latest()->first();
            // return Redirect::route('spoof.view', ['spoofId' => $spoofId->id]); //profile
            return Redirect::route('InProgress');
        }
    }
    // Messages
    public function Messages(Request $request)
    {
        $user = auth()->user();
        $messages = Messages::where('to_user_id', $user->id);

        if ($request->from || $request->date || $request->subject) {
            if ($request->from) {
                $messages = $messages;
            }
            if ($request->date) {
                // $messages->whereDate('created_at', $request->date);
                $formattedDate = '%' . date('Y-m-d', strtotime($request->date)) . '%';
                $messages->where('created_at', 'like', $formattedDate);
            }
            if ($request->subject) {
                // $messages->where('subject', $request->subject);
                $messages->where('subject', 'like', '%' . $request->subject . '%');
            }
        }
        $messages = $messages->get();
        // echo'<pre>';
        // print_r($request->subject ? $request->subject : '');

        $domains = Domain::where('user_id', $user->id)->get();

        return Inertia::render('Messages', [
            'info' => $user,
            'messages' => $messages,
            'domains' => $domains
        ]);
    }

    // public function Messages(Request $request)
    // {
    //     $request->validate([
    //         'from' => 'max:255',
    //         'date' => 'date',
    //         'subject' => 'max:255',
    //     ]);

    //     $user = auth()->user();
    //     $messages = Messages::where('to_user_id', $user->id);
    //     $domains = Domain::where('user_id', $user->id)->get();
    //     if($request->from != '' || $request->date != '' || $request->subject != ''){
    //         if($request->from != ''){
    //             $messages = $messages;
    //         }
    //         if($request->date != ''){
    //             $messages = $messages->whereDate('created_at', $request->date);
    //         }
    //         if($request->subject != ''){
    //             $messages = $messages->where('subject', $request->subject);
    //         }
    //         $messages->get();
    //     }else{
    //         $messages->get();
    //     }
    //     return Inertia::render('Messages', [
    //         'info' => $user,
    //         'messages' => $messages,
    //         'domains' => $domains
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'domains' => 'required|string'
    //     ]);

    //     $domains = explode(",", $request->domains);
    //     foreach ($domains as $domain) {
    //         $created =  Domain::create([
    //             'domain_name' => trim($domain),
    //             'user_id' => auth()->id(),
    //         ]);

    //         ScanDomains::dispatch([
    //             'domain_id' => $created->id
    //         ]);
    //     }
    // }
    //stop take down
    public function stopTakedown(Request $request): RedirectResponse
    {
        ReportformTakedownDetails::where('id', $request->theId)->delete();

        return Redirect::route('domains');
    }
}
