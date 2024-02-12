<?php

namespace App\Services;

class SearchEngines implements ScanInterface
{
    public static function search($name, $ref_id = null): array
    {
        $domain__ = explode(".", $name);
        if (!empty($domain__[0])) {
            $name = trim($domain__[0]);
        }

        // chdir($basePath);
        $originalDir = getcwd();

        // Command
        chdir(base_path('OtherDomainGenerationALgorithims'));

        $main_file =  'keywords.txt';

        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);

        $command = 'python3 searchengines.py';

        shell_exec($command);
        chdir($originalDir);

        $domainList = [];
        $handle = fopen(base_path('OtherDomainGenerationALgorithims/results.txt'), "r");
        if ($handle) {

            while (($file_line = fgets($handle)) !== false) {
                // process the file_line read.
                $file_line = str_replace(array("\r", "\n"), '', $file_line);
                array_push($domainList, $file_line);
            }

            fclose($handle);
        }

        return $domainList;
    }
}
