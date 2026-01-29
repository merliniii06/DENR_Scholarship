<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ($show_placeholder ?? false) ? 'Application Form' : 'Application Type' }} - {{ config('app.name', 'DENR Scholarship') }}</title>
    <link rel="stylesheet" href="{{ asset('css/application_type.css') }}">
</head>
<body>
    <div class="container {{ ($show_placeholder ?? false) ? 'container--narrow' : '' }}">
        @if($show_placeholder ?? false)
            <div class="header">
                <h1>Application Form</h1>
                <div class="type-badge">
                    {{ ucfirst(str_replace('_', ' ', $type ?? 'Unknown')) }}
                </div>
            </div>

            <div class="info">
                <p><strong>Application Type Selected:</strong> {{ ucfirst(str_replace('_', ' ', $type ?? 'Unknown')) }}</p>
                <p>This is where your application form will be displayed. You can now create the form fields based on the selected application type.</p>
            </div>

            <div class="links-wrap">
                <a href="{{ url('/apply') }}" class="back-link">← Back to Application Types</a>
                <span class="separator">|</span>
                <a href="{{ url('/home') }}" class="back-link">Back to Home</a>
            </div>
        @else
            <div class="header">
                <h1>Choose Application Type</h1>
                <p>Please select the type of application you want to submit</p>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error">
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

            <div class="links-wrap">
                <a href="{{ url('/home') }}" class="back-link">← Back to Home</a>
            </div>
        @endif
    </div>

    @if(!($show_placeholder ?? false))
    <script>
        function selectType(type) {
            window.location.href = '/apply/form?type=' + type;
        }
    </script>
    @endif
</body>
</html>
