<?php

namespace App\Services;

class DomainRating implements RatingInterface
{
    public static function rate($name): string
    {
       $originalDir = getcwd();

       // Command
       chdir( base_path('domainsimilarity') );

        $main_file =  'keywords.txt';
        //  Log::alert($main_file);
        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);

       $command = 'python3 domainrating.py';

       $output = shell_exec($command);
        chdir($originalDir);

          // Decode the JSON output
        $data = $output; 
        
         // Check if the JSON decoding was successful
         if ($data === null) {
            throw new \Exception("Error finding similarity");
        }
       // Assign each value to a variable
   
        return  $data;
    }

    public static function dbColumnName(): string
    {
        return "domain_rating";
    }
}
