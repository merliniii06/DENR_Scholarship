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
                <div class="number" id="stat-total">{{ $applications->count() }}</div>
            </div>
            <a href="{{ route('admin.today') }}" class="stat-card stat-card--link" title="View today's applications">
                <h3>Today's Applications</h3>
                <div class="number" id="stat-today">{{ $applications->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</div>
            </a>
            <a href="{{ route('admin.week') }}" class="stat-card stat-card--link" title="View this week's applications">
                <h3>This Week</h3>
                <div class="number" id="stat-week">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())->count() }}</div>
            </a>
            <a href="{{ route('admin.month') }}" class="stat-card stat-card--link" title="View this month's applications">
                <h3>This Month</h3>
                <div class="number" id="stat-month">{{ $applications->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())->count() }}</div>
            </a>
        </div>
        
        <!-- Auto-refresh indicator -->
        <div class="auto-refresh-indicator" id="refresh-indicator">
            <span class="pulse"></span> Live updates enabled
        </div>

        <!-- Applications Table -->
        <div class="applications-section">
            <div class="section-header">
                <h2>{{ $section_title ?? 'All Applications' }}</h2>
                @if($show_filter_link ?? false)
                    <a href="{{ url('/admin_home') }}" class="view-all-link">← View all applications</a>
                @endif
            </div>

            <div class="type-filters">
                <span class="filter-label">Filter by Type:</span>
                <a href="{{ url('/admin_home') }}" class="filter-btn {{ !isset($active_filter) ? 'active' : '' }}">All</a>
                <a href="{{ url('/admin_home?filter=denr_scholar') }}" class="filter-btn filter-denr {{ ($active_filter ?? '') === 'denr_scholar' ? 'active' : '' }}">DENR Scholar</a>
                <a href="{{ url('/admin_home?filter=study') }}" class="filter-btn filter-study {{ ($active_filter ?? '') === 'study' ? 'active' : '' }}">Study</a>
                <a href="{{ url('/admin_home?filter=non_study') }}" class="filter-btn filter-non-study {{ ($active_filter ?? '') === 'non_study' ? 'active' : '' }}">Non-Study</a>
                <a href="{{ url('/admin_home?filter=permit_to_study') }}" class="filter-btn filter-permit {{ ($active_filter ?? '') === 'permit_to_study' ? 'active' : '' }}">Permit to Study</a>
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
                            <th>Type</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Phone</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="applications-tbody">
                        @foreach($applications as $application)
                            <tr>
                                <td>
                                    @php
                                        $typeClass = match($application->application_type) {
                                            'DENR Scholar' => 'type-denr',
                                            'Study/Non-Study' => 'type-study',
                                            'Permit to Study' => 'type-permit',
                                            default => 'type-study'
                                        };
                                    @endphp
                                    <span class="type-badge {{ $typeClass }}">
                                        {{ $application->application_type }}
                                        @if($application->study_type)
                                            ({{ $application->study_type }})
                                        @endif
                                    </span>
                                </td>
                                <td><strong>{{ $application->full_name }}</strong></td>
                                <td>{{ $application->email }}</td>
                                <td>{{ $application->position }}</td>
                                <td>{{ $application->office }}</td>
                                <td>{{ $application->phone_number }}</td>
                                <td>{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y') }}</td>
                                <td>
                                    <div class="action-btns">
                                        <a href="#" class="view-btn" onclick="viewApplication({{ $application->id }}, '{{ $application->application_type }}')">View Details</a>
                                        <form action="{{ url('/admin/applications/' . $application->id . '/delete') }}" method="POST" class="action-form" onsubmit="return confirm('Delete this application? This cannot be undone.');">
                                            @csrf
                                            @php
                                                $deleteType = match($application->application_type) {
                                                    'DENR Scholar' => 'denr_scholar',
                                                    'Study/Non-Study' => 'study_non_study',
                                                    'Permit to Study' => 'permit_to_study',
                                                    default => 'denr_scholar'
                                                };
                                            @endphp
                                            <input type="hidden" name="type" value="{{ $deleteType }}">
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

        function viewApplication(id, type) {
            const application = applications.find(app => app.id == id && app.application_type == type);
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

            let studyTypeHtml = '';
            if (application.application_type === 'Study/Non-Study' && application.study_type) {
                studyTypeHtml = `<div style="margin-bottom: 15px;"><strong>Study Type:</strong> ${application.study_type}</div>`;
            }

            const modalContent = `
                <div style="line-height: 1.8;">
                    <div style="margin-bottom: 15px;"><strong>Application Type:</strong> <span class="modal-type-badge">${application.application_type}</span></div>
                    ${studyTypeHtml}
                    <div style="margin-bottom: 15px;"><strong>Full Name:</strong> ${application.full_name}</div>
                    <div style="margin-bottom: 15px;"><strong>Age:</strong> ${application.age}</div>
                    <div style="margin-bottom: 15px;"><strong>Gender/Sex:</strong> ${application.gender || 'N/A'}</div>
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

        const currentFilter = '{{ $active_filter ?? '' }}';
        const csrfToken = '{{ csrf_token() }}';
        let lastCount = applications.length;

        function getTypeClass(appType) {
            switch(appType) {
                case 'DENR Scholar': return 'type-denr';
                case 'Study/Non-Study': return 'type-study';
                case 'Permit to Study': return 'type-permit';
                default: return 'type-study';
            }
        }

        function getDeleteType(appType) {
            switch(appType) {
                case 'DENR Scholar': return 'denr_scholar';
                case 'Study/Non-Study': return 'study_non_study';
                case 'Permit to Study': return 'permit_to_study';
                default: return 'denr_scholar';
            }
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr);
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        function renderApplicationRow(app) {
            const typeClass = getTypeClass(app.application_type);
            const deleteType = getDeleteType(app.application_type);
            const studyTypeLabel = app.study_type ? ` (${app.study_type})` : '';
            
            return `
                <tr>
                    <td>
                        <span class="type-badge ${typeClass}">
                            ${app.application_type}${studyTypeLabel}
                        </span>
                    </td>
                    <td><strong>${app.full_name}</strong></td>
                    <td>${app.email}</td>
                    <td>${app.position}</td>
                    <td>${app.office}</td>
                    <td>${app.phone_number}</td>
                    <td>${formatDate(app.created_at)}</td>
                    <td>
                        <div class="action-btns">
                            <a href="#" class="view-btn" onclick="viewApplication(${app.id}, '${app.application_type}')">View Details</a>
                            <form action="/admin/applications/${app.id}/delete" method="POST" class="action-form" onsubmit="return confirm('Delete this application? This cannot be undone.');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="type" value="${deleteType}">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateDashboard() {
            const url = '/admin/api/applications' + (currentFilter ? '?filter=' + currentFilter : '');
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) return;

                    // Update stats
                    document.getElementById('stat-total').textContent = data.stats.total;
                    document.getElementById('stat-today').textContent = data.stats.today;
                    document.getElementById('stat-week').textContent = data.stats.week;
                    document.getElementById('stat-month').textContent = data.stats.month;

                    // Update applications array for modal
                    applications.length = 0;
                    data.applications.forEach(app => applications.push(app));

                    // Check if count changed
                    if (data.applications.length !== lastCount) {
                        lastCount = data.applications.length;
                        
                        // Update table
                        const tbody = document.getElementById('applications-tbody');
                        if (tbody && data.applications.length > 0) {
                            tbody.innerHTML = data.applications.map(renderApplicationRow).join('');
                        }

                        // Flash the indicator
                        const indicator = document.getElementById('refresh-indicator');
                        indicator.classList.add('updated');
                        setTimeout(() => indicator.classList.remove('updated'), 1000);
                    }
                })
                .catch(err => console.log('Polling error:', err));
        }

        // Poll every 5 seconds
        setInterval(updateDashboard, 5000);
    </script>
</body>
</html>
