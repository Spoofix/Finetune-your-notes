<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e5e2e2;
            width: 100%;
            /* Light background color */
        }

        .container {
            max-width: 500px;
            height: 500px;
            margin: 20px auto;
            background-color: #ffffff;
            /* White container background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #0e0e0f;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji',
                'Segoe UI Emoji', 'Segoe UI Symbol';
            font-size: 16px;
            /* Light shadow */
        }



        .logo {
            /* Yellow-300 */
            font-size: 38px;
            font-weight: 900;
            display: flex;
            width: 100%;
            height: 60px;
            border-radius: 6px;
            background: var(--default-black, linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), linear-gradient(0deg, rgba(0, 0, 0, 0.20) 0%, rgba(0, 0, 0, 0.20) 100%), #0C0C0C);
            flex-shrink: 0;
            line-height: 120%;
            /* 72px */
        }

        .heading {
            color: #262525;
            font-size: 19px;
            font-weight: 600;
        }

        .content {

            padding: 20px;
            /* color: #333; */
            /* Dark text color */
        }

        .button {
            padding: 14px 20px;
            background-color: #FFD633;
            /* Yellow-300 */
            color: #000000;
            /* White text color */
            text-decoration: none;
            font-weight: bold;
            font-size: 20px;
            border-radius: 4px;
            height: 34px;
            text-align: center;
            border: #0e0e0f;
        }

        .button:hover {
            /* Yellow-300 */
            transition-duration: 400ms;
            transition-delay: 20ms;
            box-shadow: #000000;
            background-color: #ffcc00;
            color: #343333;

        }

        .footer {
            text-align: center;
            color: #000000;
            width: 100%;
            margin: 140px;
            font-size: 9px;

            /* Gray text color */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo ">
            <div style="color: #ffbc12; padding-top: 10px; margin-left: 5px;">Spoo</div>
            <div style="color: #ffffff; padding-top: 10px;">fix</div>
        </div>
        <div class="content">
            <p>Dear {{--heading.name--}},</p>
            <p>I trust this email finds you well.</p>
            <p>We are writing to formally report a spoofing incident involving {{-- spoofData.spoofed_domain --}}
                , which we
                have detected on spoofix.com. This unauthorized usage poses a significant security risk and undermines
                the integrity of our online presence.</p>
            <p>Attached are screenshots documenting the spoofing activity for your immediate review and action. We
                kindly request your prompt investigation and resolution of this matter to ensure the safety of our
                online assets and users.</p>
            <p>Your swift attention to this issue is greatly appreciated.</p>
            <p>Thank you.</p>
            <p>Best regards,<br>
                <span class="capitalize">{{--userName--}},</span><br>
                Support,<br>
                <a href="mailto:support@spoofix.com" class="text-blue-700">support@spoofix.com</a>

            </p>
        </div>
    </div>
    </div>

</body>

</html>