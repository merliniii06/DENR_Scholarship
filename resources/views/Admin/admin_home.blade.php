<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'DENR Scholarship') }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin_home.css') }}">
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
            <a href="{{ route('admin.today') }}" class="stat-card stat-card--link" title="View today's applications">
                <h3>Today's Applications</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</div>
            </a>
            <a href="{{ route('admin.week') }}" class="stat-card stat-card--link" title="View this week's applications">
                <h3>This Week</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
            </a>
            <a href="{{ route('admin.month') }}" class="stat-card stat-card--link" title="View this month's applications">
                <h3>This Month</h3>
                <div class="number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count() }}</div>
            </a>
        </div>

        <!-- Applications Table -->
        <div class="applications-section">
            <div class="section-header">
                <h2>{{ $section_title ?? 'All Applications' }}</h2>
                @if($show_filter_link ?? false)
                    <a href="{{ url('/admin_home') }}" class="view-all-link">← View all applications</a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

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
                                    <div class="action-btns">
                                        <a href="#" class="view-btn" onclick="viewApplication({{ $application->id }})">View Details</a>
                                        <form action="{{ url('/admin/applications/' . $application->id . '/delete') }}" method="POST" class="action-form" onsubmit="return confirm('Delete this application? This cannot be undone.');">
                                            @csrf
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>
                                    </div>
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
    <div id="applicationModal" class="application-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Application Details</h2>
                <button type="button" onclick="closeModal()" class="modal-close-btn">&times;</button>
            </div>
            <div id="modalContent" class="modal-body"></div>
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
