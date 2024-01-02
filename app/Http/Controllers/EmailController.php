<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SpoofDomainReporting;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Send report email to multiple addresses.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request)
    {
        $emailAddresses = $request->input('emails');

        if ($this->hasInternetConnection()) {
            $subject = 'Your Email Subject is hello';
            $data = ['name' => 'Recipient Name']; // Replace with any data needed for the email view

            $fromEmail = 'info@spoofix.com';
            $fromName = 'ernest'; // Replace with your name or any desired sender name
            $fromOrganization = 'Spoofix'; // Replace with your organization name
            $employeePosition = 'Employee Position'; // Replace with the employee position

            foreach ($emailAddresses as $email) {
                Mail::to($email)->send(new SpoofDomainReporting($data, $subject));
            }

            return response()->json(['message' => 'All emails sent.'], 200);
        } else {
            return response()->json(['message' => 'No internet connection. Unable to send emails.'], 500);
        }
    }

    /**
     * Check if there is an internet connection.
     *
     * @return bool
     */
    protected function hasInternetConnection()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        }
        return false;
    }
}
