<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use App\Models\DomainDetail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\SpoofedDomain;

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
        return Inertia::render('InProgress', [
            'spoofList' => $IdsList,
            'domainList' => Domain::where('user_id', auth()->id())->get()
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
            if ($last_batch) {
                $list = $list->where('last_batch', $last_batch->last_batch);
            }
            $list = $list->get();
            $IdsList = array_merge($IdsList, $list->toArray());
        }
        return Inertia::render('Completed', [
            'spoofList' => $IdsList,
            'domainList' => Domain::where('user_id', auth()->id())->get()
        ]);
    }
}
