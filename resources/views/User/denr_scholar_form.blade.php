<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENR Scholar Application - {{ config('app.name', 'DENR Scholarship') }}</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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

        .header p {
            color: #666;
        }

        .form-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
        }

        .form-section {
            display: flex;
            flex-direction: column;
        }

        .form-section h3 {
            color: #333;
            font-size: 1.3em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group label .required {
            color: red;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group input[type="tel"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="tel"]:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1em;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .form-group select:hover {
            border-color: #667eea;
        }

        .form-group input[type="file"] {
            padding: 8px;
            cursor: pointer;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 0.9em;
        }

        .file-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .file-group label {
            color: #495057;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
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

        @media (max-width: 1024px) {
            .form-layout {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .container {
                padding: 20px;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DENR Scholar Application</h1>
            <p>Please fill out all required fields</p>
        </div>

        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('/apply/denr-scholar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-layout">
                <!-- Left Side: Fill Up Form -->
                <div class="form-section">
                    <h3>Personal Information</h3>

                    <div class="form-group">
                        <label for="fullname">Full Name <span class="required">*</span></label>
                        <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                        @error('fullname')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="age">Age <span class="required">*</span></label>
                        <input type="number" id="age" name="age" value="{{ old('age') }}" min="1" max="120" required>
                        @error('age')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender/Sex <span class="required">*</span></label>
                        <select id="gender" name="gender" required>
                            <option value="">-- Select Gender/Sex --</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            <option value="Prefer not to say" {{ old('gender') == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        @error('gender')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position">Position <span class="required">*</span></label>
                        <input type="text" id="position" name="position" value="{{ old('position') }}" required>
                        @error('position')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="office">Office <span class="required">*</span></label>
                        <select id="office" name="office" required>
                            <option value="">-- Select Office --</option>
                            <option value="RO - Administrative Division" {{ old('office') == 'RO - Administrative Division' ? 'selected' : '' }}>RO - Administrative Division</option>
                            <option value="RO - Legal Division" {{ old('office') == 'RO - Legal Division' ? 'selected' : '' }}>RO - Legal Division</option>
                            <option value="RO - Planning and Management Division" {{ old('office') == 'RO - Planning and Management Division' ? 'selected' : '' }}>RO - Planning and Management Division</option>
                            <option value="RO - Finance Division" {{ old('office') == 'RO - Finance Division' ? 'selected' : '' }}>RO - Finance Division</option>
                            <option value="RO - Licenses, Patents and Deeds Division" {{ old('office') == 'RO - Licenses, Patents and Deeds Division' ? 'selected' : '' }}>RO - Licenses, Patents and Deeds Division</option>
                            <option value="RO - Conservative and Development Division" {{ old('office') == 'RO - Conservative and Development Division' ? 'selected' : '' }}>RO - Conservative and Development Division</option>
                            <option value="RO - Enforcement Division" {{ old('office') == 'RO - Enforcement Division' ? 'selected' : '' }}>RO - Enforcement Division</option>
                            <option value="RO - RSCIG" {{ old('office') == 'RO - RSCIG' ? 'selected' : '' }}>RO - RSCIG</option>
                            <option value="RO - Office of the Directors" {{ old('office') == 'RO - Office of the Directors' ? 'selected' : '' }}>RO - Office of the Directors</option>
                            <option value="PENRO Oriental Mindoro" {{ old('office') == 'PENRO Oriental Mindoro' ? 'selected' : '' }}>PENRO Oriental Mindoro</option>
                            <option value="CENRO, Socorro" {{ old('office') == 'CENRO, Socorro' ? 'selected' : '' }}>CENRO, Socorro</option>
                            <option value="CENRO, Roxas, Oriental Mindoro" {{ old('office') == 'CENRO, Roxas, Oriental Mindoro' ? 'selected' : '' }}>CENRO, Roxas, Oriental Mindoro</option>
                            <option value="PENRO Occidental Mindoro" {{ old('office') == 'PENRO Occidental Mindoro' ? 'selected' : '' }}>PENRO Occidental Mindoro</option>
                            <option value="CENRO Sablayan" {{ old('office') == 'CENRO Sablayan' ? 'selected' : '' }}>CENRO Sablayan</option>
                            <option value="CENRO San Jose" {{ old('office') == 'CENRO San Jose' ? 'selected' : '' }}>CENRO San Jose</option>
                            <option value="PENRO Marinduque" {{ old('office') == 'PENRO Marinduque' ? 'selected' : '' }}>PENRO Marinduque</option>
                            <option value="PENRO Romblon" {{ old('office') == 'PENRO Romblon' ? 'selected' : '' }}>PENRO Romblon</option>
                            <option value="PENRO Palawan" {{ old('office') == 'PENRO Palawan' ? 'selected' : '' }}>PENRO Palawan</option>
                            <option value="CENRO Puerto Princesa" {{ old('office') == 'CENRO Puerto Princesa' ? 'selected' : '' }}>CENRO Puerto Princesa</option>
                            <option value="CENRO Quezon" {{ old('office') == 'CENRO Quezon' ? 'selected' : '' }}>CENRO Quezon</option>
                            <option value="CENRO Roxas, Palawan" {{ old('office') == 'CENRO Roxas, Palawan' ? 'selected' : '' }}>CENRO Roxas, Palawan</option>
                            <option value="CENRO Tagaytay" {{ old('office') == 'CENRO Tagaytay' ? 'selected' : '' }}>CENRO Tagaytay</option>
                            <option value="CENRO Brooke's Point" {{ old('office') == 'CENRO Brooke\'s Point' ? 'selected' : '' }}>CENRO Brooke's Point</option>
                            <option value="CENRO Coron" {{ old('office') == 'CENRO Coron' ? 'selected' : '' }}>CENRO Coron</option>
                        </select>
                        @error('office')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phonenumber">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') }}" required>
                        @error('phonenumber')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- Right Side: Required Documents -->
                <div class="form-section">
                    <h3>Required Documents</h3>

                    <div class="file-group">
                        <label for="file1">IPCR <span class="required">*</span></label>
                        <input type="file" id="file1" name="file1" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file1')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file2">Invitation Letter <span class="required">*</span></label>
                        <input type="file" id="file2" name="file2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file2')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file3">Nomination Letter <span class="required">*</span></label>
                        <input type="file" id="file3" name="file3" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file3')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file4">Service Record <span class="required">*</span></label>
                        <input type="file" id="file4" name="file4" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file4')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file5">Certificate of No Pending Admin Case <span class="required">*</span></label>
                        <input type="file" id="file5" name="file5" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file5')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file6">PDS <span class="required">*</span></label>
                        <input type="file" id="file6" name="file6" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file6')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file7">Self-Certification of Travel History <span class="required">*</span></label>
                        <input type="file" id="file7" name="file7" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file7')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file8">Others <span class="required">*</span></label>
                        <input type="file" id="file8" name="file8" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file8')
                            <small style="color: red;">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div style="grid-column: 1 / -1; margin-top: 30px;">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                    <a href="{{ url('/apply') }}" class="btn btn-secondary" style="text-decoration: none; text-align: center;">Cancel</a>
                </div>
            </div>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/home') }}" class="back-link">← Back to Home</a>
        </div>
    </div>
</body>
</html>
