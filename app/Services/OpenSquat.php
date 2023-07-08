<?php

namespace App\Services;
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

use App\Models\OrgDomain;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Notifications\AlertOnNewDomain;
// use Illuminate\Support\Facades\Storage;
// use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
class OpenSquat
{
    public static function createfile()
    {

    }

    public static function search($name, $org_id)
    {

        $main_file = base_path('opensquat\keywords.txt');
        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);

        //Add domain to file
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);
        //moving to the opensquat directory
        //  base_path('opensquat');
        //  chdir($basePath);
        // Command
        $command = 'python '.base_path('opensquat\opensquat.py');

       $return = shell_exec($command);
       Log::alert("= ".$return);




        $handle = fopen(base_path('opensquat\results.txt'), "r");
        if ($handle) {
            $org = Organization::where("id", $org_id)->first();
            $domainList = [];
            while (($file_line = fgets($handle)) !== false) {
                // process the file_line read.
                $file_line = str_replace(array("\r", "\n"), '', $file_line);
                $domain = OrgDomain::where('name', $file_line)->where('organization_id', $org_id)->first();
                if (!$domain) {
                    array_push($domainList, $file_line);
                    OrgDomain::create(['name' => $file_line, 'organization_id' => $org_id]);
                }
            }

            if (count($domainList) > 1) {
                $org->user->notify((new AlertOnNewDomain($org->name, count($domainList)))->delay(now()->addSeconds(30)));
            }
            fclose($handle);
        }

        return true;

    }

    public static function find($name)
    {
        // chdir($basePath);
        $originalDir = getcwd();

       // Command
       chdir( base_path('opensquat') );

        $main_file =  'keywords.txt';
        Log::alert($main_file);
        $file = fopen($main_file, 'w');
        ftruncate($file, 0);
        fclose($file);
        $current = file_get_contents($main_file);
        $current .= $name;
        file_put_contents($main_file, $current);

        //delete everything in the results.txt
        // $file2 = 'results.txt';
        // File::put($file2, '');
        //moving to the opensquat directory

       
        

        // ...
        
        // function executeCommandAndWait()
        // {
        //     $command = shell_exec("cat keywords.txt");
        //     getcwd(); // Get the current working directory
        
        //     $fullCommand =  $command; // Append the command to the current directory
        //     // $pythonExecutable = 'C:\Program Files\Python311\python.exe';
        //     // c:\Program Files\Python311

        //     $process = Process::fromShellCommandline($fullCommand);
        //     $process->setTimeout(null); // Remove timeout limit
        //     // set_time_limit(0); // Set maximum execution time limit to 0
        //     $process->run();
        //     if (!$process->isSuccessful()) {
        //         // throw new \RuntimeException('Command failed: ' . $process->getErrorOutput());
        //         return shell_exec("cat keywords.txt");
        //     }
        
        //     // Command executed successfully, and you can access the output using $process->getOutput()
        
        //     return 'hello there';
        // }
        
        //  return  executeCommandAndWait();
       $command = 'python opensquat.py';



       //.base_path('opensquat\opensquat.py');
    // Log::alert( base_path('opensquat') );
    //    Log::alert($command);
    //    exec($command, $output, $return);
    //    return  $return = shell_exec("cat keywords.txt");
    //  shell_exec("python opensquat.py");

    // if($return){
    //     return $return = shell_exec("cat keywords.txt");
    // }

    // return  $return = shell_exec("python opensquat.py");
        shell_exec($command);
        chdir($originalDir);

        // Log::error($return);

        $handle = fopen(base_path('opensquat\results.txt'), "r");
        if ($handle) {

            $domainList = [];
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
