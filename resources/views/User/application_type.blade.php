<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Type - {{ config('app.name', 'DENR Scholarship') }}</title>
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
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .application-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .application-type-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .application-type-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-color: #667eea;
            background: #fff;
        }

        .application-type-card:active {
            transform: translateY(-2px);
        }

        .application-type-card h3 {
            color: #333;
            font-size: 1.3em;
            margin-bottom: 10px;
        }

        .application-type-card p {
            color: #666;
            font-size: 0.95em;
            line-height: 1.5;
        }

        .icon {
            font-size: 3em;
            margin-bottom: 15px;
        }

        .back-link {
            display: inline-block;
            margin-top: 30px;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }

            .header h1 {
                font-size: 2em;
            }

            .application-types {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Choose Application Type</h1>
            <p>Please select the type of application you want to submit</p>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <div class="application-types">
            <a href="#" class="application-type-card" onclick="selectType('denr_scholar')">
                <div class="icon">🎓</div>
                <h3>DENR Scholar</h3>
                <p>Apply for DENR Scholarship program</p>
            </a>

            <a href="#" class="application-type-card" onclick="selectType('study_non_study')">
                <div class="icon">📚</div>
                <h3>Study / Non-Study</h3>
                <p>Apply for Study or Non-Study program</p>
            </a>

            <a href="#" class="application-type-card" onclick="selectType('permit_to_study')">
                <div class="icon">📋</div>
                <h3>Permit to Study</h3>
                <p>Request permit to study</p>
            </a>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/home') }}" class="back-link">← Back to Home</a>
        </div>
    </div>

    <script>
        function selectType(type) {
            // Redirect to application form with type parameter
            // This will call a Controller method on the server
            window.location.href = '/apply/form?type=' + type;
        }
    </script>
</body>
</html>
