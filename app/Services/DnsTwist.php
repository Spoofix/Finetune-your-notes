<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class DnsTwist implements ScanInterface
{
    public static function search($domain, $scan_id=null): array
    {
        // Define the directory to save the screenshots
//        ini_set("max_execution_time", 600);
        // $domainW = explode('.', $domain)[0];
//         $dir = public_path("assets/screenshots/" . $domain);
// //        info($dir);
//         try {
//             if (!is_dir($dir)) {
//                 mkdir($dir);
//             }
//             $dir .= '/' . $scan_id;
//             mkdir($dir);
//         } catch (\Exception $ex) {
// //            info($ex->getMessage());
//         }

        // Run dnstwist command and capture output
        // $command = "dnstwist  {$domain} --format json";
        // $output = shell_exec($command);
        
        $listOutput = shell_exec("dnstwist --format list {$domain}");
        Log::info(json_encode($listOutput ));
        $domainsArray = explode("\n", trim($listOutput));
        
        $domainsArray = array_filter($domainsArray);
        $spoofs = [];

            foreach ($domainsArray as $domain) {
                if (strpos($domain, 'xn--') === 0) {
                    continue; 
                }elseif(!checkdnsrr($domain)){
                    continue;
                }
        
              
                $domainsFound = $domain;
                array_push($spoofs, $domainsFound);
            }
            Log::info(json_encode($spoofs ));
             return $spoofs;

    }
     
}

        // // Decode the JSON output
        // $result = json_decode($output, true);

        // Check if the JSON decoding was successful
        // if ($result === null) {
        //     throw new \Exception("Error decoding JSON data");
        // }

        // $spoofs = [];
        // Iterate through the results

        // foreach ($result as $entry) {

            // if (!isset($entry['phash'])) {
            //     continue;
            // }

            //  dd(self::screenshot($dir, $entry));
//            Log::error(self::screenshot($dir, $entry));
            // Extract and store the relevant data for each entry
            // $screenshot = self::screenshot($dir, $entry);
            // $phashes = $entry['phash'] ?? 'none';
            // $geoips = $entry['geoip'] ?? 'none';
            // $htmls = $entry['ssdeep'] ?? 'none';
            // $domainsFound = $entry['fuzzer'] === 'subdomain' ? $entry['domain'] : 'none';

            // $resultArray = [
            //     // $screenshot,
            //     // $phashes,
            //     // $geoips,
            //     // $htmls,
            //     $domainsFound,
            //     // $dir
            // ];

            // array_push($spoofs, $resultArray);
            // array_push($spoofs, $domainsFound);

        // }
    


        // return $domainsArray;
        // dd($spoofs);
        // Now you have arrays containing the extracted data, and you can use them as needed.
        // Or you can loop through all the domains and their respective data:

        // return $spoofs;
      
    // }

    // public static function screenshot($dir, $entry)
    // {
    //     $files = scandir($dir);
    //     $files = array_diff($files, array('.', '..'));
    //     $desiredFileName = $entry['domain'];
    //     $screenshot = '';

    //     foreach ($files as $file) {
    //         // dd($file);
    //         $file_clone = $file;
    //         $file = explode('_', $file);
    //         $file = isset($file[1]) ? $file[1] : '';
    //         $file = str_replace('.png', '', $file);
    //         // dd($desiredFileName);
    //         if ($file === $desiredFileName) {
    //             $screenshot = $file_clone;
    //             Log::error($screenshot);
    //             // dd($screenshot);
    //             break;
    //         }
    //     }
    //     // dd($dir . DIRECTORY_SEPARATOR . $screenshot);
    //     return $dir . DIRECTORY_SEPARATOR . $screenshot;
    // }

// }
