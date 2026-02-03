<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENR Scholar Application - {{ config('app.name', 'DENR Scholarship') }}</title>
    <link rel="stylesheet" href="{{ asset('css/denr_scholar_form.css') }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DENR Scholar Application</h1>
            <p>Please fill out all required fields</p>
        </div>

        @if(session('error'))
            <div class="alert-error">
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
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="age">Age <span class="required">*</span></label>
                        <input type="number" id="age" name="age" value="{{ old('age') }}" min="1" max="120" required>
                        @error('age')
                            <small class="error">{{ $message }}</small>
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
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position">Position <span class="required">*</span></label>
                        <input type="text" id="position" name="position" value="{{ old('position') }}" required>
                        @error('position')
                            <small class="error">{{ $message }}</small>
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
                            <option value="CENRO Taytay" {{ old('office') == 'CENRO Taytay' ? 'selected' : '' }}>CENRO Taytay</option>
                            <option value="CENRO Brooke's Point" {{ old('office') == 'CENRO Brooke\'s Point' ? 'selected' : '' }}>CENRO Brooke's Point</option>
                            <option value="CENRO Coron" {{ old('office') == 'CENRO Coron' ? 'selected' : '' }}>CENRO Coron</option>
                        </select>
                        @error('office')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phonenumber">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') }}" required>
                        @error('phonenumber')
                            <small class="error">{{ $message }}</small>
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
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file2">Invitation Letter <span class="required">*</span></label>
                        <input type="file" id="file2" name="file2" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file2')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file3">Nomination Letter <span class="required">*</span></label>
                        <input type="file" id="file3" name="file3" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file3')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file4">Service Record <span class="required">*</span></label>
                        <input type="file" id="file4" name="file4" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file4')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file5">Certificate of No Pending Admin Case <span class="required">*</span></label>
                        <input type="file" id="file5" name="file5" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file5')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file6">PDS w/ WES <span class="required">*</span></label>
                        <input type="file" id="file6" name="file6" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file6')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file7">Self-Certification of Travel History <span class="required">*</span></label>
                        <input type="file" id="file7" name="file7" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        @error('file7')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="file-group">
                        <label for="file8">Others <span class="optional">(optional)</span></label>
                        <input type="file" id="file8" name="file8" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        @error('file8')
                            <small class="error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                    <a href="{{ url('/apply') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>

        <div class="links-wrap">
            <a href="{{ url('/home') }}" class="back-link">← Back to Home</a>
        </div>
    </div>
</body>
</html>
