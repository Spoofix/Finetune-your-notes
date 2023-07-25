<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class csscolor
{
    public static function rate($name, $id) //$id
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


          $command = 'python3 csscolor.py';
    
          $output = shell_exec($command);
            chdir($originalDir);

       
            $wordToSearch = "crating";
            $replacement = "crating_"; // Note: Do not include the dynamic part here.
            
            $newData = preg_replace('/\b' . preg_quote($wordToSearch, '/') . '(_\d+(\.\d+)?)?\b/', $replacement, $output);
            $parts = explode('_', $newData);
            $dynamicPart = end($parts); 
         // Check if the JSON decoding was successful
         if ($parts === null) {
            throw new \Exception("Error finding similarity");
        }
       // Assign each value to a variable
       
    
   
        $result = $dynamicPart;

     
        return $result;
      
    }
}