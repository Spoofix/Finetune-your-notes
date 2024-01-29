<?php

namespace App\Jobs;

use App\Models\ReportformTakedownDetails;
use App\Models\SpoofedDomain;
use App\Services\LocationSslAndRedirects;
use App\Traits\RatingAlreadyScanned;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LocationSslAndRedirectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RatingAlreadyScanned;

    private $spoofed_domain;
    public $tries = 5;
    public $tag;
    /**
     * Create a new job instance.
     * @param $dt
     */
    public function __construct(SpoofedDomain $dt)
    {
        $this->spoofed_domain = $dt;
        $this->tag = "ScanDomains" . $dt->domain_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // if ($this->copyRating($this->spoofed_domain, "locationSslAndRedirects")) {
        //     Log::alert('hello');
        //     return;
        // }

        $pythonOutput = LocationSslAndRedirects::search($this->spoofed_domain->spoofed_domain);
        Log::alert('ni hear');
        // Log::alert($pythonOutput);

        $outputArray = json_decode($pythonOutput, true);

        $phpOutput = [];

        foreach ($outputArray as $entry) {
            // Extract the key and value from the entry
            foreach ($entry as $key => $value) {
                // Clean up the key to match PHP variable names
                $cleanedKey = str_replace([' ', ':'], ['', '_'], $key);

                // Handle arrays
                if (is_array($value)) {
                    // Recursively process the nested array
                    $phpOutput[$cleanedKey] = LocationSslAndRedirectsJob::handleNestedArray($value);
                } else {
                    // Assign the cleaned key and value to the PHP output array
                    $phpOutput[$cleanedKey] = $value;
                }
            }
        }

        $ipAddress = $phpOutput['IP_Address'] ?? '';
        $serverCity = $phpOutput['Geolocation_Information']['City'] ?? '';
        $region = $phpOutput['Geolocation_Information']['Region'] ?? '';
        $serverCountry = $phpOutput['Geolocation_Information']['Country'] ?? '';
        $latitude = $phpOutput['Geolocation_Information']['Latitude'] ?? '';
        $longitude = $phpOutput['Geolocation_Information']['Longitude'] ?? '';
        $organization = $phpOutput['Geolocation_Information']['Organization'] ?? '';
        $isp = $phpOutput['Geolocation_Information']['ISP'] ?? '';
        $sslCertificateDetails = $phpOutput['SSL_Certificate_Details'] ?? '';
        $redirectUrls = $phpOutput['Redirects'] ?? '';
        $httpStatusCode = $phpOutput['HTTP_Status_Code'] ?? '';
        $cookies = $phpOutput['Cookies'] ?? '';
        $consoleMessages = $phpOutput['Console_Messages'] ?? '';
        $securityHeaders = $phpOutput['Security_Headers'] ?? '';
        $serverOs = $phpOutput['Web_Server'] ?? '';


        $this->spoofed_domain->ip_address = $ipAddress;
        $this->spoofed_domain->server_city = $serverCity;
        $this->spoofed_domain->region = $region;
        $this->spoofed_domain->server_country = $serverCountry;
        $this->spoofed_domain->latitude  = $latitude;
        $this->spoofed_domain->longitude = $longitude;
        $this->spoofed_domain->organization = $organization;
        $this->spoofed_domain->isp  = $isp;
        $this->spoofed_domain->ssl_certificate_details  = $sslCertificateDetails;
        $this->spoofed_domain->redirect_urls = $redirectUrls;
        $this->spoofed_domain->http_status_code  = $httpStatusCode;
        // $this->spoofed_domain->cookies = $cookies;
        // $this->spoofed_domain->console_messages = $consoleMessages;
        $this->spoofed_domain->security_headers = $securityHeaders;
        $this->spoofed_domain->server_os  = $serverOs;
        $this->spoofed_domain->spoof_status = 'REPORTED';
        $isInprogress = ReportformTakedownDetails::where('evidence_urls', $this->spoofed_domain->spoofed_domain);
        if ($isInprogress) {
            $this->spoofed_domain->spoof_status_new = 'inprogress';
        } else {
            $this->spoofed_domain->current_scan_status = 'scanned';
        }
        $this->spoofed_domain->save();
        Log::alert('ni hear the end');
    }
    public static function handleNestedArray($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            // Handle further nesting if the value is an array
            if (is_array($value)) {
                $result[$key] = LocationSslAndRedirectsJob::handleNestedArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
