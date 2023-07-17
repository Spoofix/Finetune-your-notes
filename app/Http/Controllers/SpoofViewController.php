<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SpoofedDomain;

class SpoofViewController extends Controller
{
    public function spoofView($spoofId){

        $spoofData = SpoofedDomain::where('id',$spoofId)->first();
    
        
        return Inertia::render('Domain/View', [
                'spoofData' => $spoofData
        ]);
    }
}
