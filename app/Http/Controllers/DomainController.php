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
        // $ReportForm = new ReportformTakedownDetails;

        // $request->user()->fill($request->validated());

        // $ReportForm->abuse_type = $request->abuse_type;
        // $ReportForm->evidence_urls = $request->evidence_urls;
        // $ReportForm->additional_notes = $request->additional_notes;
        // $ReportForm->observed_date = $request->observed_date;
        // $ReportForm->attachments = $request->attachments;
        // $ReportForm->carbon_copy_request_to = $request->carbon_copy_request_to;
        // $ReportForm->reported_by_user_id = $request->reported_by_user_id;
        // $ReportForm->reported_via = $request->reported_via;
        // $ReportForm->save();

        // $request->validate([
        //     'abuse_type' => 'required',
        //     'evidence_urls' => 'required',
        //     'additional_notes' => 'nullable',
        //     'observed_date' => 'nullable',
        //     'attachments' => 'nullable',
        //     'carbon_copy_request_to' => 'nullable',
        //     'reported_by_user_id' => 'required',
        //     'reported_via' => 'required',
        // ]);

        // if ($request->hasFile('attachment')) {
        //     $attachment = $request->file('attachment');
        //     $fileName = time() . '-' . $attachment->getCurrentOrigimalName();
        //     $attachment->storeAs('attachments', $fileName, 'public');

        //     $validatedData['attachment'] = $fileName;
        // }

        ReportformTakedownDetails::create([
            'abuse_type' => $request->abuse_type,
            'evidence_urls' => $request->evidence_urls,
            'additional_notes' => $request->additional_notes,
            'observed_date' => $request->observed_date,
            'attachments' => $request->attachments,
            'carbon_copy_request_to' => $request->carbon_copy_request_to,
            'reported_via' => $request->reported_via,
            'reported_by_user_id' => auth()->id(),
        ]);

        $target_domain = Domain::where('domain_name', $request->targetDomain)->get();

        $spoofDomain = $request->evidence_urls;
        if ($request->reported_via !== 'report_form') {
            $domain_id = $request->reported_via;
            $spoofDomain = SpoofedDomain::where('id', $request->id)->first();
            $spoofDomain->spoof_status_new = 'inprogress';
            $spoofDomain->save();
            return Redirect::route('spoof.view', ['spoofId' => $request->id]);
        } else {
            $domain_id = $target_domain->first()->id;
            $last_batch = time();
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

            ]);

            ScanDomainRatings::dispatch($created);
            LocationSslAndRedirectsJob::dispatch($created);
            WhoIsRating::dispatch($created);



            $spoofId = SpoofedDomain::latest()->first();
            return Redirect::route('spoof.view', ['spoofId' => $spoofId->id]); //profile
        }
    }
    // Messages
    public function Messages(Request $request)
    {
        return Inertia::render('Messages', []);
    }

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
}
