<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

    <div class="container ">
        <div class="logo ">
            <div style="color: #ffbc12; padding-top: 10px; margin-left: 5px;">Spoo</div>
            <div style="color: #ffffff; padding-top: 10px;">fix</div>
        </div>
        <div class="content">
            <div class="heading">OTP Verification Code</div>
            <p style="color: #000;">To authenticate, please use the following One Time Password (OTP):</p>

            <div style="height: 20px;"></div>
            <div style="margin: auto; width:fit-content;">
                <div class="button" >{{ $otp }}</div>
            </div>
            <div style="height: 40px;"></div>
            <p>This OTP is valid for only 15 minutes. Please copy it and verify your email.</p>
            <p>If this email is not intended to you please ignore and delete it. Thank you for understanding.</p>
        </div>
        <div style="margin-top: 20px; padding: 20px;">For further assistance , please contact our customer service
            center:
            <a href="http://127.0.0.1:8000/profile-change-password"
                style="color: #4285F4; text-decoration: none;">support@spoofixcustomercare.com</a>
        </div>
        <div style="width: 100; margin-top:10px;">
            <small class="footer">Copyright &copy; Spoofix {{ date('Y') }}. All Rights Reserved.</small>
        </div>
    </div>

</body>

</html>