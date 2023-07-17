<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PulseDive
{
    public static function search($name, $id) //$id
    {
        $domain = $name;
        $response = Http::withHeaders([
            "Content-Type" => "application/x-www-form-urlencoded; charset=UTF-8",
        ])->withOptions(['verify' => false])->get("https://pulsedive.com/api/analyze.php?value=$domain&probe=1&pretty=1&key=67dc312c2a8e663a959d915e308e6a1d0863749e327cb415f875dd8a1f3d490a");
        // var_dump( $response->json()['qid']);
        // var_dump($domain);

        $response2 = Http::withOptions(['verify' => false])->get("https://pulsedive.com/api/analyze.php?qid=" . $response->json()['qid'] . "&pretty=1&key=67dc312c2a8e663a959d915e308e6a1d0863749e327cb415f875dd8a1f3d490a");
        // var_dump($response2);
        while ($response2->json()['status'] == "processing") {
            $response2 = Http::withOptions(['verify' => false])->get("https://pulsedive.com/api/analyze.php?qid=" . $response->json()['qid'] . "&pretty=1&key=67dc312c2a8e663a959d915e308e6a1d0863749e327cb415f875dd8a1f3d490a");
            sleep(1);
            // $hello="hello";
            // var_dump($hello);
        }
        $return = $response2->json();
        // $domainjson = $return['data']['indicator'];
        $riskRating = $return['data']['risk'] ?? 'None';
        $screenshot = $return["data"]["properties"]["dom"]["screenshot"] ?? 'None';
        $rname = $return["data"]["properties"]["dns"]["rname"] ?? 'None';
        $geoCountry = $return["data"]["properties"]["geo"]["country"] ?? 'None';
        $registrationDate = $return["data"]["stamp_seen"] ?? "None";

        // Create an array and assign the variables to it
        $result = array(
            $riskRating,
            $rname,
            $screenshot,
            $geoCountry,
            $registrationDate
        );
        // $registrar = $return['data']['properties']['dns']['rname'];
        // var_dump($domainjson);
        // var_dump($result);
        // var_dump($riskRating);
        // $output = [$riskRating, $registrar];


        // exit();

        // return $riskRating;
        return $result;
    }
}
