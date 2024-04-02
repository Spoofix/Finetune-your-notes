<?php

// Hostinger SMTP configuration
$hostname = '{imap.hostinger.com:993/ssl/novalidate-cert}INBOX';
$username = 'info@spoofix.com';
$password = 'Abcdefg123456@spoofix';

// Connect to the IMAP server
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Hostinger: ' . imap_last_error());

// Get emails
$emails = imap_search($inbox, 'ALL');

if ($emails) {
    $emailsArray = [];
    $startProcessing = false;
    foreach ($emails as $email_number => $emailId) {
        if ($startProcessing) {
            $emailArray = [];
            $firstEmailId = $emailId;
            // echo $email_number . "<br/>\n";
            $emailArray['index'] = $email_number;

            if ($email_number > 6) {
                // Stop the foreach loop
                break;
            }
            // Fetch email header
            $header = imap_headerinfo($inbox, $firstEmailId);

            // Check if the email is a reply
            if (isset($header->in_reply_to) || isset($header->references)) {
                // echo "This email is a reply.\n";
                if (!empty($header->in_reply_to)) {
                    // echo "This email is a reply to: " . $header->in_reply_to . "<br/>\n";
                    $emailArray['in_reply_to'] = $header->in_reply_to;
                } else {
                    // echo "This email is a reply to: " . $header->references . "<br/>\n";
                    $emailArray['references'] = $header->references;
                }
            } else {
                // This email is not a reply
                // echo "This email is not a reply.\n";
                // Proceed with processing as necessary
            }
            // Display email details
            // echo "From: " . $header->fromaddress . "<br/>\n";
            $emailArray['fromaddress'] = $header->fromaddress;

            // echo "Subject: " . $header->subject . "<br/>\n";
            $emailArray['subject'] = $header->subject;

            // echo "Date: " . $header->date . "<br/>\n";
            $emailArray['date'] = $header->date;

            // echo "Message-Id: " . $header->message_id . "<br/>\n";
            $emailArray['message_id'] = $header->message_id;

            // $to = $header->toaddress;

            // echo "Folder: " . $header->mailbox . "<br/>\n";
            // $emailArray['mailbox'] = $header->mailbox;

            // You can fetch more details as per your requirement

            // Fetch email body (optional)
            $body = imap_fetchbody($inbox, $firstEmailId, 2);
            // echo "Body: " . $body . "<br/>\n";
            $emailArray['body'] = $body;
            $emailsArray[] = $emailArray;

            // echo "\n";
            // echo "\n";
        }
        if ($email_number == 2 && !$startProcessing) {
            // Set flag to start processing emails
            $startProcessing = true;
        }
    }
    foreach ($emailsArray as $emailArray) {
        foreach ($emailArray as $key => $value) {
            echo $key . ": " . $value . "\n";
        }
        echo "\n";
        echo "\n";
    }
} else {
    echo "No emails found\n";
}
// Close connection
imap_close($inbox);


/*0: Represents the entire message.
1: Represents the plain text version of the message (if available).
2: Represents the HTML version of the message (if available).
3: Represents the first attached message.
4: Represents the second attached message.*/
