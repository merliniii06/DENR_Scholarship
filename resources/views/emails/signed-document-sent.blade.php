<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signed Document</title>
    @if(file_exists(public_path('css/email-application-confirmed.css')))
    <style>{{ file_get_contents(public_path('css/email-application-confirmed.css')) }}</style>
    @else
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.5; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header h1 { color: #1e7e34; }
        .message { margin: 16px 0; white-space: pre-wrap; }
        .details { background: #f5f5f5; padding: 16px; border-radius: 8px; margin: 16px 0; }
        .footer { margin-top: 24px; font-size: 12px; color: #666; }
    </style>
    @endif
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DENR Scholarship</h1>
        </div>

        <div class="content">
            <p class="greeting">Dear <strong>{{ $application->full_name }}</strong>,</p>

            <p class="message">
                Please find attached your signed document for your <strong>{{ $applicationTypeName }}</strong> application.
            </p>

            <div class="details">
                <h3>Message from DENR HRDS</h3>
                <p class="message">{{ $messageText }}</p>
            </div>

            <p class="message">
                The signed document is attached to this email. Please download and keep it for your records.
            </p>

            <p class="message">
                If you have any questions, please contact the HRDS office.<br>
                Email: hrds.r4b@denr.gov.ph
            </p>

            <p style="margin-top: 32px;">
                Best regards,<br>
                <strong>DENR HRDS Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>Department of Environment and Natural Resources</p>
            <p>Human Resource Development Section - Region 4B</p>
            <p>Email: hrds.r4b@denr.gov.ph</p>
        </div>
    </div>
</body>
</html>
