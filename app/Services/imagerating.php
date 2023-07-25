<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class imagerating
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

            $command = 'python3 screenshots.py';
          $command = 'python3 image.py';
    
          $output = shell_exec($command);
            chdir($originalDir);

          // Decode the JSON output
        $data = $output; 
        
         // Check if the JSON decoding was successful
         if ($data === null) {
            throw new \Exception("Error finding similarity");
        }
       // Assign each value to a variable
       
    
   
        $result = $data;
     
        return $result;
      
    }
}