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
                <h1>DENR Dashboard</h1>
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

    <div class="layout-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg class="sidebar-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                </svg>
                <h3>Confirmed Applications</h3>
            </div>
            <div class="sidebar-folders">
                @php
                    $forFolderCounts = $folderApplications ?? $confirmedApplications;
                    $confirmedDenr = $forFolderCounts->filter(fn($app) => $app->application_type === 'DENR Scholar');
                    $confirmedStudy = $forFolderCounts->filter(fn($app) => $app->application_type === 'Study/Non-Study');
                    $confirmedPermit = $forFolderCounts->filter(fn($app) => $app->application_type === 'Permit to Study');
                    $confirmedStudyLeave = $forFolderCounts->filter(fn($app) => $app->application_type === 'Study Leave');
                    $confirmedSignedPermit = $forFolderCounts->filter(fn($app) => $app->application_type === 'Permit to Study' && !empty($app->signed_document_sent_at));
                    $confirmedSignedStudyLeave = $forFolderCounts->filter(fn($app) => $app->application_type === 'Study Leave' && !empty($app->signed_document_sent_at));
                @endphp
                
                <a href="{{ url('/admin_home/folder/denr-scholar') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'denr_scholar' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">DENR Scholar</div>
                        <div class="sidebar-folder-count">{{ $confirmedDenr->count() }} confirmed</div>
                    </div>
                </a>
                
                <a href="{{ url('/admin_home/folder/study-non-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'study_non_study' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Study / Non-Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedStudy->count() }} confirmed</div>
                    </div>
                </a>
                
                <a href="{{ url('/admin_home/folder/permit-to-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'permit_to_study' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Permit to Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedPermit->count() }} confirmed</div>
                    </div>
                </a>

                <a href="{{ url('/admin_home/folder/study-leave') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'study_leave' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Study Leave</div>
                        <div class="sidebar-folder-count">{{ $confirmedStudyLeave->count() }} confirmed</div>
                    </div>
                </a>

                <a href="{{ url('/admin_home/folder/signed-permit-to-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'signed_permit' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Signed Permit to Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedSignedPermit->count() }} sent</div>
                    </div>
                </a>

                <a href="{{ url('/admin_home/folder/signed-study-leave') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'signed_study_leave' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Signed Study Leave</div>
                        <div class="sidebar-folder-count">{{ $confirmedSignedStudyLeave->count() }} sent</div>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
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

        <!-- Folder Modal -->
        <div id="folderModal" class="folder-modal">
            <div class="folder-modal-dialog">
                <div class="folder-modal-header">
                    <h2 id="folderModalTitle">Confirmed Applications</h2>
                    <button type="button" onclick="closeFolderModal()" class="modal-close-btn">&times;</button>
                </div>
                <div id="folderModalContent" class="folder-modal-body"></div>
            </div>
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
                <a href="{{ url('/admin_home?filter=study_non_study') }}" class="filter-btn filter-study {{ ($active_filter ?? '') === 'study_non_study' ? 'active' : '' }}">Study / Non-Study</a>
                <a href="{{ url('/admin_home?filter=permit_to_study') }}" class="filter-btn filter-permit {{ ($active_filter ?? '') === 'permit_to_study' ? 'active' : '' }}">Permit to Study</a>
                <a href="{{ url('/admin_home?filter=study_leave') }}" class="filter-btn filter-study-leave {{ ($active_filter ?? '') === 'study_leave' ? 'active' : '' }}">Study Leave</a>
            </div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if(in_array($active_filter ?? '', ['signed_permit', 'signed_study_leave']))
                @php
                    $signedType = ($active_filter ?? '') === 'signed_permit' ? 'permit_to_study' : 'study_leave';
                    $signedGrouped = $applications->groupBy(fn($a) => trim($a->full_name ?? 'Unknown'));
                @endphp
                @if($signedGrouped->isEmpty())
                    <div class="no-applications">
                        <div class="no-applications-icon">📭</div>
                        <h3>No signed documents sent yet</h3>
                        <p>Signed documents will appear here after the admin uploads them for confirmed applications.</p>
                    </div>
                @else
                    <div class="confirmed-list confirmed-list--page">
                        @foreach($signedGrouped as $name => $apps)
                            @php
                                $first = $apps->first();
                                $latestDate = $apps->max('updated_at');
                                $downloadType = ($active_filter ?? '') === 'signed_permit' ? 'permit_to_study' : 'study_leave';
                            @endphp
                            <div class="confirmed-item confirmed-item--expandable">
                                <div class="confirmed-number">{{ $loop->iteration }}</div>
                                <div class="confirmed-info">
                                    <button type="button" class="confirmed-name-btn" onclick="toggleSignedFiles(this)" aria-expanded="false">
                                        {{ $first->full_name }}
                                        <span class="confirmed-name-chevron" aria-hidden="true">▼</span>
                                    </button>
                                    <div class="confirmed-details">{{ $first->email }}</div>
                                    <div class="confirmed-date">Confirmed: {{ \Carbon\Carbon::parse($latestDate)->format('n/j/Y') }}{{ $apps->count() > 1 ? ' (' . $apps->count() . ' applications)' : '' }}</div>
                                </div>
                                <div class="signed-files-panel" hidden>
                                    <div class="signed-files-label">Files sent by admin:</div>
                                    <ul class="signed-files-list">
                                        @foreach($apps as $app)
                                            <li>
                                                <a href="{{ url('/admin/applications/' . $app->id . '/download-signed-document?type=' . $downloadType) }}" target="_blank" class="signed-file-link">
                                                    {{ $apps->count() > 1 ? 'Application ' . $loop->iteration . ' – View file' : 'View file' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @elseif($applications->count() > 0)
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Status</th>
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
                                            'Study Leave' => 'type-study-leave',
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
                                <td>
                                    @php
                                        $status = $application->status ?? 'pending';
                                        $statusClass = $status === 'confirmed' ? 'status-confirmed' : 'status-pending';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y') }}</td>
                                <td>
                                    @php
                                        $appType = match($application->application_type) {
                                            'DENR Scholar' => 'denr_scholar',
                                            'Study/Non-Study' => 'study_non_study',
                                            'Permit to Study' => 'permit_to_study',
                                            'Study Leave' => 'study_leave',
                                            default => 'denr_scholar'
                                        };
                                    @endphp
                                    <div class="action-btns">
                                        <a href="#" class="view-btn" onclick="viewApplication({{ $application->id }}, '{{ $application->application_type }}')">View Details</a>
                                        @if(($application->status ?? 'pending') === 'pending')
                                            <form action="{{ url('/admin/applications/' . $application->id . '/confirm') }}" method="POST" class="action-form" onsubmit="return confirm('Confirm this application? Files will be moved to the confirmed folder.');">
                                                @csrf
                                                <input type="hidden" name="type" value="{{ $appType }}">
                                                <button type="submit" class="confirm-btn">Confirm</button>
                                            </form>
                                        @else
                                            <span class="status-badge status-confirmed">✓ Confirmed</span>
                                            @if(in_array($application->application_type, ['Permit to Study', 'Study Leave']) && empty($application->signed_document_sent_at))
                                                <button type="button" class="upload-btn" onclick="openUploadModal({{ $application->id }}, '{{ $appType }}', '{{ addslashes($application->full_name) }}')">Upload</button>
                                            @endif
                                        @endif
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

    <!-- Upload Signed Document Modal -->
    <div id="uploadModal" class="application-modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Upload Signed Document</h2>
                <button type="button" onclick="closeUploadModal()" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p id="uploadModalApplicantName" class="upload-modal-applicant"></p>
                <form id="uploadSignedForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" id="uploadFormType" value="">
                    <div class="form-group">
                        <label for="signed_file">File <span class="required">*</span></label>
                        <input type="file" id="signed_file" name="signed_file" accept=".pdf,.doc,.docx,image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="upload_message">Message to applicant <span class="required">*</span></label>
                        <textarea id="upload_message" name="message" rows="4" required placeholder="Write a message to include in the email..."></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="view-btn" onclick="closeUploadModal()">Cancel</button>
                        <button type="submit" class="confirm-btn">Send to applicant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const applications = @json($applications);
        const confirmedApplications = @json($confirmedApplications);

        function viewApplication(id, type) {
            const application = applications.find(app => app.id == id && app.application_type == type);
            if (!application) return;

            // Different file labels based on application type
            const fileLabelsByType = {
                'DENR Scholar': {
                    1: 'IPCR',
                    2: 'Invitation Letter',
                    3: 'Nomination Letter',
                    4: 'Service Record',
                    5: 'Certificate of No Pending Admin Case',
                    6: 'PDS w/ WES',
                    7: 'Self-Certification of Travel History',
                    8: 'Others'
                },
                'Study/Non-Study': {
                    1: 'IPCR',
                    2: 'Invitation Letter',
                    3: 'Nomination Letter',
                    4: 'Service Record',
                    5: 'Certificate of No Pending Admin Case',
                    6: 'PDS w/ WES',
                    7: 'Self-Certification of Travel History',
                    8: 'Others'
                },
                'Permit to Study': {
                    1: 'Request Letter',
                    2: 'IPCR',
                    3: 'Registration Form from School'
                },
                'Study Leave': {
                    1: 'Study Leave Memorandum',
                    2: 'Application for Leave',
                    3: 'Updated PDS w/ WES',
                    4: 'Proof of Review',
                    5: 'Self-Review Certification',
                    6: 'Graduate Program Registration',
                    7: "Master's Completion Certification",
                    8: 'No Pending Case Certification',
                    9: "Supervisor's Certification"
                }
            };

            const fileLabels = fileLabelsByType[application.application_type] || fileLabelsByType['DENR Scholar'];
            const maxFiles = application.application_type === 'Permit to Study' ? 3 : (application.application_type === 'Study Leave' ? 9 : 8);

            let filesHtml = '';
            for (let i = 1; i <= maxFiles; i++) {
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

        function openUploadModal(appId, appType, applicantName) {
            document.getElementById('uploadModalApplicantName').textContent = 'Sending to: ' + (applicantName || 'Applicant');
            document.getElementById('uploadSignedForm').action = '/admin/applications/' + appId + '/upload-signed-document';
            document.getElementById('uploadFormType').value = appType;
            document.getElementById('signed_file').value = '';
            document.getElementById('upload_message').value = '';
            document.getElementById('uploadModal').style.display = 'block';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        function toggleSignedFiles(btn) {
            const item = btn.closest('.confirmed-item--expandable');
            const panel = item?.querySelector('.signed-files-panel');
            const chevron = item?.querySelector('.confirmed-name-chevron');
            if (!panel) return;
            const isOpen = !panel.hidden;
            panel.hidden = isOpen;
            btn.setAttribute('aria-expanded', !isOpen);
            if (chevron) chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(-90deg)';
        }

        document.getElementById('uploadModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeUploadModal();
        });

        // Folder modal functions
        const folderTitles = {
            'denr': 'DENR Scholar - Confirmed Applications',
            'study': 'Study / Non-Study - Confirmed Applications',
            'permit': 'Permit to Study - Confirmed Applications',
            'study_leave': 'Study Leave - Confirmed Applications',
            'signed_permit': 'Signed Permit to Study - Confirmed Applications',
            'signed_study_leave': 'Signed Study Leave - Confirmed Applications'
        };

        const folderTypes = {
            'denr': 'DENR Scholar',
            'study': 'Study/Non-Study',
            'permit': 'Permit to Study',
            'study_leave': 'Study Leave',
            'signed_permit': 'Signed Permit to Study',
            'signed_study_leave': 'Signed Study Leave'
        };

        function openFolder(folderType) {
            let confirmedApps;
            if (folderType === 'signed_permit') {
                confirmedApps = confirmedApplications.filter(app =>
                    app.application_type === 'Permit to Study' && app.signed_document_sent_at
                );
            } else if (folderType === 'signed_study_leave') {
                confirmedApps = confirmedApplications.filter(app =>
                    app.application_type === 'Study Leave' && app.signed_document_sent_at
                );
            } else {
                const appType = folderTypes[folderType];
                confirmedApps = confirmedApplications.filter(app => app.application_type === appType);
            }

            document.getElementById('folderModalTitle').textContent = folderTitles[folderType];

            if (confirmedApps.length === 0) {
                const emptyMsg = (folderType === 'signed_permit' || folderType === 'signed_study_leave')
                    ? 'No signed documents sent yet'
                    : 'No confirmed applications yet';
                document.getElementById('folderModalContent').innerHTML = `
                    <div class="empty-folder">
                        <div class="empty-icon">📭</div>
                        <p>${emptyMsg}</p>
                    </div>
                `;
            } else {
                const showDownload = folderType === 'signed_permit' || folderType === 'signed_study_leave';
                const downloadRoute = (app) => {
                    const t = app.application_type === 'Permit to Study' ? 'permit_to_study' : 'study_leave';
                    return '/admin/applications/' + app.id + '/download-signed-document?type=' + encodeURIComponent(t);
                };
                let listHtml = '<div class="confirmed-list">';
                let itemsToRender = confirmedApps;
                if (showDownload) {
                    const byName = {};
                    confirmedApps.forEach(app => {
                        const key = (app.full_name || 'Unknown').trim();
                        if (!byName[key]) byName[key] = [];
                        byName[key].push(app);
                    });
                    itemsToRender = Object.values(byName);
                }
                itemsToRender.forEach((item, index) => {
                    const apps = Array.isArray(item) ? item : [item];
                    const app = apps[0];
                    const studyType = app.study_type ? ` (${app.study_type})` : '';
                    const latestDate = apps.length === 1
                        ? new Date(app.updated_at).toLocaleDateString()
                        : apps.reduce((d, a) => new Date(a.updated_at) > d ? new Date(a.updated_at) : d, new Date(0)).toLocaleDateString();
                    listHtml += `
                        <div class="confirmed-item">
                            <div class="confirmed-number">${index + 1}</div>
                            <div class="confirmed-info">
                                <div class="confirmed-name">${app.full_name}</div>
                                <div class="confirmed-details">${app.email}</div>
                                <div class="confirmed-date">Confirmed: ${latestDate}${apps.length > 1 ? ' (' + apps.length + ' applications)' : ''}</div>
                            </div>
                        </div>
                    `;
                });
                listHtml += '</div>';
                document.getElementById('folderModalContent').innerHTML = listHtml;
            }

            document.getElementById('folderModal').style.display = 'block';
        }

        function closeFolderModal() {
            document.getElementById('folderModal').style.display = 'none';
        }

        // Close folder modal when clicking outside
        document.getElementById('folderModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeFolderModal();
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
                case 'Study Leave': return 'type-study-leave';
                default: return 'type-study';
            }
        }

        function getDeleteType(appType) {
            switch(appType) {
                case 'DENR Scholar': return 'denr_scholar';
                case 'Study/Non-Study': return 'study_non_study';
                case 'Permit to Study': return 'permit_to_study';
                case 'Study Leave': return 'study_leave';
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
            const appType = getDeleteType(app.application_type);
            const studyTypeLabel = app.study_type ? ` (${app.study_type})` : '';
            const status = app.status || 'pending';
            const statusClass = status === 'confirmed' ? 'status-confirmed' : 'status-pending';
            
            let actionButton = '';
            if (status === 'pending') {
                actionButton = `
                    <form action="/admin/applications/${app.id}/confirm" method="POST" class="action-form" onsubmit="return confirm('Confirm this application? Files will be moved to the confirmed folder.');">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="type" value="${appType}">
                        <button type="submit" class="confirm-btn">Confirm</button>
                    </form>
                `;
            } else {
                const alreadySent = app.signed_document_sent_at != null && app.signed_document_sent_at !== '';
                const showUpload = (app.application_type === 'Permit to Study' || app.application_type === 'Study Leave') && !alreadySent;
                const uploadBtn = showUpload
                    ? `<button type="button" class="upload-btn" onclick="openUploadModal(${app.id}, '${appType}', '${(app.full_name || '').replace(/'/g, "\\'")}')">Upload</button>`
                    : '';
                actionButton = `<span class="status-badge status-confirmed">✓ Confirmed</span>${uploadBtn}`;
            }
            
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
                    <td><span class="status-badge ${statusClass}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></td>
                    <td>${formatDate(app.created_at)}</td>
                    <td>
                        <div class="action-btns">
                            <a href="#" class="view-btn" onclick="viewApplication(${app.id}, '${app.application_type}')">View Details</a>
                            ${actionButton}
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

                    // Update confirmed applications array for folder modal
                    confirmedApplications.length = 0;
                    if (data.confirmedApplications) {
                        data.confirmedApplications.forEach(app => confirmedApplications.push(app));
                    }

                    // Check if count changed
                    if (data.applications.length !== lastCount) {
                        lastCount = data.applications.length;
                        
                        // Update table
                        const tbody = document.getElementById('applications-tbody');
                        if (tbody && data.applications.length > 0) {
                            tbody.innerHTML = data.applications.map(renderApplicationRow).join('');
                        }

                    }
                })
                .catch(err => console.log('Polling error:', err));
        }

        // Poll every 5 seconds
        setInterval(updateDashboard, 5000);
    </script>
</body>
</html>
