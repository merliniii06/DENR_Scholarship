<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'DENR Scholarship') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8em;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
            font-size: 0.9em;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #667eea;
        }

        .applications-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.5em;
        }

        .applications-table {
            width: 100%;
            border-collapse: collapse;
        }

        .applications-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .applications-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .applications-table tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .file-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9em;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .file-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .no-applications {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .no-applications-icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .view-btn {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
        }

        .view-btn:hover {
            background: #5568d3;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .applications-table {
                font-size: 0.9em;
            }

            .applications-table th,
            .applications-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>Admin Dashboard</h1>
                <p>DENR Scholarship Management System</p>
            </div>
            <div class="header-actions">
                <form id="logout-form" action="/admin_logout" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics Cards -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Applications</h3>
                <div class="number">{{ $applications->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>Today's Applications</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>This Week</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
            </div>
            <div class="stat-card">
                <h3>This Month</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count() }}</div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="applications-section">
            <div class="section-header">
                <h2>All Applications</h2>
            </div>

            @if($applications->count() > 0)
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Phone</th>
                            <th>Submitted</th>
                            <th>Files</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>#{{ $application->id }}</td>
                                <td><strong>{{ $application->full_name }}</strong></td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->position }}</td>
                                <td>{{ $application->office }}</td>
                                <td>{{ $application->phone_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <div class="file-list">
                                        @php
                                            $fileLabels = [
                                                1 => 'IPCR',
                                                2 => 'Invitation Letter',
                                                3 => 'Nomination Letter',
                                                4 => 'Service Record',
                                                5 => 'Certificate of No Pending Admin Case',
                                                6 => 'PDS',
                                                7 => 'Self-Certification of Travel History',
                                                8 => 'Others'
                                            ];
                                        @endphp
                                        @for($i = 1; $i <= 8; $i++)
                                            @php
                                                $fileField = "file_{$i}";
                                                $filePath = $application->$fileField;
                                            @endphp
                                            @if($filePath)
                                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="file-link">
                                                    {{ $fileLabels[$i] }}
                                                </a>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="view-btn" onclick="viewApplication({{ $application->id }})">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-applications">
                    <div class="no-applications-icon">📋</div>
                    <h3>No Applications Yet</h3>
                    <p>Applications submitted by users will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Application Details Modal -->
    <div id="applicationModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
        <div style="background: white; margin: 50px auto; max-width: 800px; padding: 30px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="color: #333;">Application Details</h2>
                <button onclick="closeModal()" style="background: #f0f0f0; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 1.2em;">&times;</button>
            </div>
            <div id="modalContent"></div>
        </div>
    </div>

    <script>
        const applications = @json($applications);

        function viewApplication(id) {
            const application = applications.find(app => app.id == id);
            if (!application) return;

            const fileLabels = {
                1: 'IPCR',
                2: 'Invitation Letter',
                3: 'Nomination Letter',
                4: 'Service Record',
                5: 'Certificate of No Pending Admin Case',
                6: 'PDS',
                7: 'Self-Certification of Travel History',
                8: 'Others'
            };

            let filesHtml = '';
            for (let i = 1; i <= 8; i++) {
                const fileField = `file_${i}`;
                if (application[fileField]) {
                    filesHtml += `<div style="margin: 5px 0;">
                        <a href="/storage/${application[fileField]}" target="_blank" class="file-link">${fileLabels[i]}</a>
                    </div>`;
                }
            }

            const modalContent = `
                <div style="line-height: 1.8;">
                    <div style="margin-bottom: 15px;"><strong>Full Name:</strong> ${application.full_name}</div>
                    <div style="margin-bottom: 15px;"><strong>Age:</strong> ${application.age}</div>
                    <div style="margin-bottom: 15px;"><strong>Gender/Sex:</strong> ${application.gender || application.ipcr || 'N/A'}</div>
                    <div style="margin-bottom: 15px;"><strong>Email:</strong> ${application.email}</div>
                    <div style="margin-bottom: 15px;"><strong>Position:</strong> ${application.position}</div>
                    <div style="margin-bottom: 15px;"><strong>Office:</strong> ${application.office}</div>
                    <div style="margin-bottom: 15px;"><strong>Phone Number:</strong> ${application.phone_number}</div>
                    <div style="margin-bottom: 15px;"><strong>Submitted:</strong> ${new Date(application.created_at).toLocaleString()}</div>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0;">
                        <strong>Uploaded Files:</strong>
                        <div style="margin-top: 10px;">${filesHtml || 'No files uploaded'}</div>
                    </div>
                </div>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
            document.getElementById('applicationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('applicationModal').style.display = 'none';
        }

        // Close modal when clicking outside
        document.getElementById('applicationModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
