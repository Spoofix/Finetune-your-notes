<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LocationSslAndRedirects
{
    public static function search($name) //$id
    {

        $originalDir = getcwd();

        try {
            // Change the current working directory
            chdir(base_path('domainsimilarity'));

            $main_file = 'keywords.txt';

            // Open the file in write mode, truncate, and write new content
            file_put_contents($main_file, $name);

            // Run Python scripts
            $command = 'python3 redirects.py';
            $command2 = 'python3 locationssletc.py';

            shell_exec($command);
            $data = shell_exec($command2);

            // Read the contents of the results file
            $readfile = 'results.txt';

            $dataReturned = file_get_contents($readfile);
            // Log::alert($dataReturned);
        } finally {
            // back to orginaldir
            chdir($originalDir);
        }

        return $dataReturned;
        // return $data;
    }
}
