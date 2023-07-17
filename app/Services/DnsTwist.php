<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
class DnsTwist
{
    public static function dnstwist(){
 

$domain = "facebook.com"; // Replace with your domain

// Define the directory to save the screenshots
$screenshotDirectory = '/assets/screenshots';

// Run dnstwist command and capture output
$command = "dnstwist --phash --screenshots --lsh --geoip public/assets/screenshots google.com --format json";
// $command = "dnstwist --format json domain.name | jq";
$output = shell_exec($command);

// Decode the JSON output
$result = json_decode($output, true);

// Extract screenshot filename
// $screenshot = $result['phish']['screenshot'];
// $phash = $result['phish']['phash'];
// $screenshotPercentage = $result['phish']['similarity'];

// Display the screenshot
// echo "<img src='/{$screenshotDirectory}/{$screenshot}' alt='Screenshot'>";

dd($result);
exit;
return $result;

}
}