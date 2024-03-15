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
            'name' => "Google SafeBrowsing",
            'email' => " ",
            'form_url' => "https://safebrowsing.google.com/safebrowsing/report_phish/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Microsoft",
            'email' => "phish@office365.microsoft.com",
            'form_url' => "https://www.microsoft.com/en-us/wdsi/support/report-unsafe-site-guest",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Cloudfare",
            'email' => "",
            'form_url' => "https://abuse.cloudflare.com/registrar_tm",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Phishing Database",
            'email' => "",
            'form_url' => "",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Global Cyber Alliance",
            'email' => "contact@globalcyberalliance.org",
            'form_url' => "",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "fortguard",
            'email' => "submitspam@fortinet.com",
            'form_url' => "https://www.fortiguard.com/webfilter",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "BrightCloud",
            'email' => " ",
            'form_url' => "https://www.brightcloud.com/tools/url-ip-lookup.php",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "CRDF",
            'email' => " ",
            'form_url' => "https://threatcenter.crdf.fr/submit_url.html",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Netcraft",
            'email' => " ",
            'form_url' => "https://report.netcraft.com/report",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Palo Alto Networks ",
            'email' => " ",
            'form_url' => "https://urlfiltering.paloaltonetworks.com/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "ESET",
            'email' => "samples@eset.com",
            'form_url' => "https://phishing.eset.com/en-us/report",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Trend Micro",
            'email' => "spam@support.trendmicro.com",
            'form_url' => "https://global.sitesafety.trendmicro.com/index.php",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "BitDefender",
            'email' => "",
            'form_url' => "https://www.bitdefender.com/consumer/support/answer/29358/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "McAfee",
            'email' => "reportphishing@mcafee.com", //phishing@mcafee.com
            'form_url' => "https://sitelookup.mcafee.com/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Forcepoint",
            'email' => "",
            'form_url' => "https://csi.forcepoint.com/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Symantec",
            'email' => "spamsample@symantec.com",
            'form_url' => "https://sitereview.symantec.com/#/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Spam404 ",
            'email' => " ",
            'form_url' => "https://www.spam404.com/report.html",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Kaspersky",
            'email' => " ",
            'form_url' => "https://opentip.kaspersky.com/",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "Cisco Talos",
            'email' => "phish@access.ironport.com",
            'form_url' => "https://talosintelligence.com/reputation_center",
            'Can_Report' => "true"

        ]);
        Reporting::create([
            'name' => "APWG (Anti-Phishing Working Group)",
            'email' => "reportphishing@apwg.org",
            'form_url' => "",
            'Can_Report' => "true"
        ]);
    }
}
