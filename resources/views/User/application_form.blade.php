<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form - {{ config('app.name', 'DENR Scholarship') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .type-badge {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 10px;
        }

        .info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info p {
            color: #666;
            line-height: 1.6;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Application Form</h1>
            <div class="type-badge">
                {{ ucfirst(str_replace('_', ' ', $type ?? 'Unknown')) }}
            </div>
        </div>

        <div class="info">
            <p><strong>Application Type Selected:</strong> {{ ucfirst(str_replace('_', ' ', $type ?? 'Unknown')) }}</p>
            <p style="margin-top: 10px;">This is where your application form will be displayed. You can now create the form fields based on the selected application type.</p>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/apply') }}" class="back-link">← Back to Application Types</a>
            <span style="margin: 0 10px;">|</span>
            <a href="{{ url('/home') }}" class="back-link">Back to Home</a>
        </div>
    </div>
</body>
</html>
