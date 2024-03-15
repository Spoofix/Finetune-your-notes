<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhoIs
{
    public static function search($name, $id)
    {
        $originalDir = getcwd();

        // Command
        chdir(base_path('domainsimilarity'));

        $main_file =  'keywords.txt';
        //  Log::alert($main_file);
        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);


        $command = 'python3 webrist.py';

        $output = shell_exec($command);
        chdir($originalDir);

        // Decode the JSON output
        $data = json_decode($output, true);
        Log::alert($output);

        // Check if the JSON decoding was successful
        if ($data === null) {
            throw new \Exception("Error decoding JSON data");
        }

        // Assign each value to a variable
        $webrisk_response = isset($data["Unsafe URL"]) ? $data["Unsafe URL"] : '';
        $webrisk_ranking = isset($data["Safe URL"]) ? $data["Safe URL"] : '';

        if ($webrisk_response) {
            $webrisk_response = "Unsafe URL";
            $webrisk_ranking = $webrisk_response;
        } else if ($webrisk_ranking) {
            $webrisk_response = "Safe URL";
            $webrisk_ranking = $webrisk_ranking;
        }

        // Log or further process the values as needed
        $result = array(
            "webrisk_response" => $webrisk_response,
            "webrisk_ranking" => $webrisk_ranking
        );



        return $result;
    }
}
