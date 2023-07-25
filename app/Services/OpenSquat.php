<?php

namespace App\Services;

class OpenSquat implements ScanInterface
{
    public static function search($name, $ref_id=null) : array
    {
        // chdir($basePath);
        $originalDir = getcwd();

        // Command
        chdir( base_path('opensquat') );

        $main_file =  'keywords.txt';

        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);

        $command = 'python3 opensquat.py';

        shell_exec($command);
        chdir($originalDir);

        $domainList = [];
        $handle = fopen(base_path('opensquat/results.txt'), "r");
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
