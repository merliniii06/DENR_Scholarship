<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Confirmed</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header {
            background: #2d5a47;
            color: white;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 16px;
        }
        .message {
            color: #4a4a4a;
            margin-bottom: 24px;
        }
        .details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 24px 0;
        }
        .details h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .details p {
            margin: 8px 0;
            color: #1a1a1a;
        }
        .details strong {
            color: #2d5a47;
        }
        .badge {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
        }
        .footer {
            padding: 24px 32px;
            background: #f8f9fa;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
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
                If you have any questions, please contact the HRDS office.
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
