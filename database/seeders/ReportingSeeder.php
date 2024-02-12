<?php

namespace Database\Seeders;

use App\Models\Reporting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reporting::create([
            'report_to' => "Google SafeBrowsing ",
            'email' => " ",
            'link_to_form' => "https://safebrowsing.google.com/safebrowsing/report_phish/"
        ]);
        Reporting::create([
            'report_to' => "Microsoft",
            'email' => "phish@office365.microsoft.com",
            'link_to_form' => "https://www.microsoft.com/en-us/wdsi/support/report-unsafe-site-guest"
        ]);
        Reporting::create([
            'report_to' => "fortguard",
            'email' => "submitspam@fortinet.com",
            'link_to_form' => "https://www.fortiguard.com/webfilter"
        ]);
        Reporting::create([
            'report_to' => "BrightCloud",
            'email' => " ",
            'link_to_form' => "https://www.brightcloud.com/tools/url-ip-lookup.php"
        ]);
        Reporting::create([
            'report_to' => "CRDF",
            'email' => " ",
            'link_to_form' => "https://threatcenter.crdf.fr/submit_url.html"
        ]);
        Reporting::create([
            'report_to' => "Netcraft",
            'email' => " ",
            'link_to_form' => "https://report.netcraft.com/report"
        ]);
        Reporting::create([
            'report_to' => "Palo Alto Networks ",
            'email' => " ",
            'link_to_form' => "https://urlfiltering.paloaltonetworks.com/"
        ]);
        Reporting::create([
            'report_to' => "ESET",
            'email' => "samples@eset.com",
            'link_to_form' => "https://phishing.eset.com/en-us/report"
        ]);
        Reporting::create([
            'report_to' => "Trend Micro",
            'email' => "spam@support.trendmicro.com",
            'link_to_form' => "https://global.sitesafety.trendmicro.com/index.php"
        ]);
        Reporting::create([
            'report_to' => "BitDefender",
            'email' => "",
            'link_to_form' => "https://www.bitdefender.com/consumer/support/answer/29358/"
        ]);
        Reporting::create([
            'report_to' => "McAfee",
            'email' => "reportphishing@mcafee.com", //phishing@mcafee.com
            'link_to_form' => "https://sitelookup.mcafee.com/"
        ]);
        Reporting::create([
            'report_to' => "Forcepoint",
            'email' => "",
            'link_to_form' => "https://csi.forcepoint.com/"
        ]);
        Reporting::create([
            'report_to' => "Symantec",
            'email' => "spamsample@symantec.com",
            'link_to_form' => "https://sitereview.symantec.com/#/"
        ]);
        Reporting::create([
            'report_to' => "Spam404 ",
            'email' => " ",
            'link_to_form' => "https://www.spam404.com/report.html"
        ]);
        Reporting::create([
            'report_to' => "Kaspersky",
            'email' => " ",
            'link_to_form' => "https://opentip.kaspersky.com/"
        ]);
        Reporting::create([
            'report_to' => "Cisco Talos",
            'email' => "phish@access.ironport.com",
            'link_to_form' => "https://talosintelligence.com/reputation_center"
        ]);
        Reporting::create([
            'report_to' => "Anti-Phishing Working Group",
            'email' => "reportphishing@apwg.org",
            'link_to_form' => ""
        ]);
    }
}
