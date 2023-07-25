<?php

namespace App\Services;

class ContentRating implements RatingInterface
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

        $command = 'python3 contentrating.py';
    
        $output = shell_exec($command);
        chdir($originalDir);
        
        // Convert the output to a string
        $output_str = (string)$output;
        
        // Check if the output has less than 6 characters
        if (strlen($output_str) < 6) {
           $last_line = $output;
        } else {
            // Split the output into lines
            $data  = explode(PHP_EOL, $output);

            // Get the last line
            $last_line = trim(end($data));
        }

         // Check if the JSON decoding was successful
         if ($last_line === null) {
            throw new \Exception("Error finding html similarity");
         }
       // Assign each value to a variable

        return $last_line;
    }

    public static function dbColumnName(): string
    {
        return "htmls";
    }
}