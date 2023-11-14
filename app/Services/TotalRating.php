<?php

namespace App\Services;

use DateTime;
use App\Models\Weight;
use App\Models\SpoofedDomain;

class TotalRating
{
    public static function rating($spoofId) 
    {
        // spoof id penye inaenda
        $spoofData = SpoofedDomain::where('id',$spoofId)->first();
        //weights
        $screensortr =  $spoofData->where('phashes');
        $colorr =  $spoofData->where('csscolor');
        $contentr =  $spoofData->where('domainsimilarityrate');
        $domainr =  $spoofData->where('domain_rating');
        $htmlr =  $spoofData->where('htmls');
        $ager =  $spoofData->where('registrationDate');
        $ager2 = calculateTimeDifferenceInDays($ager);
        $ager3 = (366 - $ager2)/366;
            //age calculation
            function calculateTimeDifferenceInDays($dateString) {
                if (is_array($dateString)) {
                    if (isset($dateString[0]) && is_string($dateString[0])) {
                        // If the $dateString is an array, get the first date from the array
                        $firstDateString = $dateString[0];
                        $firstDate = new DateTime($firstDateString);
                    } else {
                        return 'none';
                    }
                } elseif (is_string($dateString)) {
                    $firstDate = new DateTime($dateString);
                } else {
                    return 'none';
                }
            
                $currentDate = new DateTime();
            
                // Calculate the time difference in seconds
                $diffInSeconds = $currentDate->getTimestamp() - $firstDate->getTimestamp();
            
                // Calculate the time difference in days
                $diffInDays = floor($diffInSeconds / (24 * 60 * 60));
            
                return $diffInDays;
            }
            
        // $trafficr =  $spoofData->where('traffic');
    $screensortW = Weight::where('factors', 'Ui rating')->first()->weights;
    $colorW = Weight::where('factors', 'Color Scheme')->first()->weights;
    $contentW = Weight::where('factors', 'Content')->first()->weights;
    $domainW = Weight::where('factors', 'Domain')->first()->weights;
    $htmlW = Weight::where('factors', 'Html')->first()->weights;
    $ageW = Weight::where('factors', 'Age')->first()->weights;
    //pending
    // $reputationW = Weight::where('factors', 'reputation')->first()->weights;

    $screensort = $screensortW /100 * $screensortr;
    $Color = $colorW /100 * $colorr;
    $Content = $contentW /100 * $contentr;
    $Domain = $domainW /100 * $domainr ;
    $HTML = $htmlW / 100 * $htmlr;
    $Age = $ageW / 100 * $ager3;
    // $reputation = $reputationW  / 100 * $reputationr;
    $weight = $screensortW + $colorW + $contentW + $domainW + $htmlW + $ageW; //$reputationW
    if($weight === 100){
        $rating = $screensort + $Color + $Content + $Domain + $HTML + $Age ; //$Traffic, Reputation
        return $rating;
    }
    // $Traffic = 17.5 / 100 * $trafficr
    }

    public static function dbColumnName(): string
    {
        return 'rating';
    }
}