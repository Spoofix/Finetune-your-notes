<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhoIs
{
    public static function search($name, $id) //$id
    {


// whois new.py
           // chdir($basePath);
           $originalDir = getcwd();

           // Command
           chdir( base_path('whois') );

            $main_file =  'keywords.txt';
            //  Log::alert($main_file);
            $file = fopen($main_file, 'w');
            ftruncate($file, 0);
            fclose($file);
            $current = file_get_contents($main_file);
            $current .= $name;
            file_put_contents($main_file, $current);


          $command = 'python3 new.py';
    
          $output = shell_exec($command);
            chdir($originalDir);

          // Decode the JSON output
        $data = json_decode($output, true); 
        
         // Check if the JSON decoding was successful
         if ($data === null) {
            throw new \Exception("Error decoding JSON data");
        }
       // Assign each value to a variable
       
       $domain_name = isset($data["domain_name"]) ?  (is_array($data["domain_name"]) ? json_encode($data["domain_name"]) : $data["domain_name"]) :'';
       $registrar = isset($data["registrar"]) ?  (is_array($data["registrar"]) ? json_encode($data["registrar"]) : $data["registrar"]) :'';
       $whois_server = isset($data["whois_server"]) ?  (is_array($data["whois_server"]) ? json_encode($data["whois_server"]) : $data["whois_server"]) :'';
       $referral_url = isset($data["referral_url"]) ?  (is_array($data["referral_url"]) ? json_encode($data["referral_url"]) : $data["referral_url"]) :'';
       $updated_date = isset($data["updated_date"]) ?  (is_array($data["updated_date"]) ? json_encode($data["updated_date"]) : $data["updated_date"]) :'';
       $creation_date = isset($data["creation_date"]) ?  (is_array($data["creation_date"]) ? json_encode($data["creation_date"]) : $data["creation_date"]) :'';
       $expiration_date = isset($data["expiration_date"]) ?  (is_array($data["expiration_date"]) ? json_encode($data["expiration_date"]) : $data["expiration_date"]) :'';
       $name_servers = isset($data["name_servers"][0]) ?  (is_array($data["name_servers"][0]) ? json_encode($data["name_servers"][0]) : $data["name_servers"][0]) :'';
       $status = isset($data["status"]) ?  (is_array($data["status"]) ? json_encode($data["status"]) : $data["status"]) :'';
       $emails = isset($data["emails"]) ?  (is_array($data["emails"]) ? json_encode($data["emails"]) : $data["emails"]) :'';
       $dnssec = isset($data["dnssec"]) ?  (is_array($data["dnssec"]) ? json_encode($data["dnssec"]) : $data["dnssec"]) :'';
       $name = isset($data["name"]) ?  (is_array($data["name"]) ? json_encode($data["name"]) : $data["name"]) :'';
       $org = isset($data["org"]) ?  (is_array($data["org"]) ? json_encode($data["org"]) : $data["org"]) :'';
       $address = isset($data["address"]) ?  (is_array($data["address"]) ? json_encode($data["address"]) : $data["address"]) :'';
       $state = isset($data["state"]) ?  (is_array($data["state"]) ? json_encode($data["state"]) : $data["state"]) :'';
       $registrant_postal_code = isset($data["registrant_postal_code"]) ?  (is_array($data["registrant_postal_code"]) ? json_encode($data["registrant_postal_code"]) : $data["registrant_postal_code"]) :'';
       $country = isset($data["country"]) ?  (is_array($data["country"]) ? json_encode($data["country"]) : $data["country"]) :'';
       $city = isset($data["city"]) ?  (is_array($data["city"]) ? json_encode($data["city"]) : $data["city"]) :'';
       
   
        $result = array(
            $domain_name,
            $registrar,
            $whois_server,
            $referral_url,
            $updated_date,
            $creation_date,
            $expiration_date,
            $name_servers,
            $status,
            $emails,
            $dnssec,
            $name,
            $org,
            $address,
            $city,
            $state,
            $registrant_postal_code,
            $country,
         );
     
        return $result;
      
    }
}
