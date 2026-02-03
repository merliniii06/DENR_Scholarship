<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Confirmed</title>
    <style>{{ file_get_contents(public_path('css/email-application-confirmed.css')) }}</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DENR Scholarship</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Dear <strong>{{ $application->full_name }}</strong>,</p>
            
            <p class="message">
                We are pleased to inform you that your application has been <span class="badge">Confirmed</span> by the administrator.
            </p>
            
            <div class="details">
                <h3>Application Details</h3>
                <p><strong>Application Type:</strong> {{ $applicationType }}</p>
                <p><strong>Full Name:</strong> {{ $application->full_name }}</p>
                <p><strong>Email:</strong> {{ $application->email }}</p>
                <p><strong>Office:</strong> {{ $application->office }}</p>
                <p><strong>Position:</strong> {{ $application->position }}</p>
                <p><strong>Confirmed On:</strong> {{ now()->format('F d, Y h:i A') }}</p>
            </div>
            
            <p class="message">
                Your submitted documents have been reviewed and processed. Please keep this email for your records.
            </p>
            
            <p class="message">
                If you have any questions, please contact the HRDS office. <br>
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
            <p style="margin-top: 8px; font-size: 12px;">For inquiries, please reply to this email or contact the HRDS office.</p>
        </div>
    </div>
</body>
</html>
