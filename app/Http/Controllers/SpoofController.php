<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Domain;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;

class SpoofController extends Controller
{
    public function spoof($domainId){

        $last_batch = SpoofedDomain::where('domain_id',$domainId)->orderBy('id','desc')->first();
        $list =  SpoofedDomain::where('domain_id',$domainId);
        if($last_batch){
            $list = $list->where('last_batch',$last_batch->last_batch);
        }
        $list = $list->get();
        return Inertia::render('Organization/Index', [
            'spoofList' =>$list,
            'domainList' => Domain::where('user_id',auth()->id())->get()
        ]);
    }
}
