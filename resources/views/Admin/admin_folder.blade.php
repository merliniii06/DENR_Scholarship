<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $section_title }} - {{ config('app.name', 'DENR Scholarship') }}</title>
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
        <aside class="sidebar">
            <div class="sidebar-header">
                <svg class="sidebar-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                </svg>
                <h3>Confirmed Applications</h3>
            </div>
            <div class="sidebar-folders">
                @php
                    $confirmedDenr = $confirmedApplications->filter(fn($app) => $app->application_type === 'DENR Scholar');
                    $confirmedStudy = $confirmedApplications->filter(fn($app) => $app->application_type === 'Study/Non-Study');
                    $confirmedPermit = $confirmedApplications->filter(fn($app) => $app->application_type === 'Permit to Study');
                    $confirmedStudyLeave = $confirmedApplications->filter(fn($app) => $app->application_type === 'Study Leave');
                    $confirmedSignedPermit = $confirmedApplications->filter(fn($app) => $app->application_type === 'Permit to Study' && !empty($app->signed_document_sent_at));
                    $confirmedSignedStudyLeave = $confirmedApplications->filter(fn($app) => $app->application_type === 'Study Leave' && !empty($app->signed_document_sent_at));
                @endphp
                <a href="{{ url('/admin_home/folder/denr-scholar') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'denr_scholar' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">DENR Scholar</div>
                        <div class="sidebar-folder-count">{{ $confirmedDenr->count() }} confirmed</div>
                    </div>
                </a>
                <a href="{{ url('/admin_home/folder/study-non-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'study_non_study' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Study / Non-Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedStudy->count() }} confirmed</div>
                    </div>
                </a>
                <a href="{{ url('/admin_home/folder/permit-to-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'permit_to_study' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Permit to Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedPermit->count() }} confirmed</div>
                    </div>
                </a>
                <a href="{{ url('/admin_home/folder/study-leave') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'study_leave' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Study Leave</div>
                        <div class="sidebar-folder-count">{{ $confirmedStudyLeave->count() }} confirmed</div>
                    </div>
                </a>
                <a href="{{ url('/admin_home/folder/signed-permit-to-study') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'signed_permit' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Signed Permit to Study</div>
                        <div class="sidebar-folder-count">{{ $confirmedSignedPermit->count() }} sent</div>
                    </div>
                </a>
                <a href="{{ url('/admin_home/folder/signed-study-leave') }}" class="sidebar-folder sidebar-folder-link {{ ($active_filter ?? '') === 'signed_study_leave' ? 'sidebar-folder--active' : '' }}">
                    <div class="sidebar-folder-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/></svg></div>
                    <div class="sidebar-folder-info">
                        <div class="sidebar-folder-name">Signed Study Leave</div>
                        <div class="sidebar-folder-count">{{ $confirmedSignedStudyLeave->count() }} sent</div>
                    </div>
                </a>
            </div>
        </aside>

        <div class="main-content">
            <div class="applications-section">
                <div class="section-header">
                    <h2>{{ $section_title }}</h2>
                    <a href="{{ url('/admin_home') }}" class="view-all-link">← Back to dashboard</a>
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
                        <tbody>
                            @foreach($applications as $application)
                                <tr>
                                    @php
                                        $typeClass = match($application->application_type) {
                                            'DENR Scholar' => 'type-denr',
                                            'Study/Non-Study' => 'type-study',
                                            'Permit to Study' => 'type-permit',
                                            'Study Leave' => 'type-study-leave',
                                            default => 'type-study'
                                        };
                                        $appType = match($application->application_type) {
                                            'DENR Scholar' => 'denr_scholar',
                                            'Study/Non-Study' => 'study_non_study',
                                            'Permit to Study' => 'permit_to_study',
                                            'Study Leave' => 'study_leave',
                                            default => 'denr_scholar'
                                        };
                                    @endphp
                                    <td><span class="type-badge {{ $typeClass }}">{{ $application->application_type }}@if($application->study_type) ({{ $application->study_type }})@endif</span></td>
                                    <td><strong>{{ $application->full_name }}</strong></td>
                                    <td>{{ $application->email }}</td>
                                    <td>{{ $application->position }}</td>
                                    <td>{{ $application->office }}</td>
                                    <td><span class="status-badge {{ ($application->status ?? 'pending') === 'confirmed' ? 'status-confirmed' : 'status-pending' }}">{{ ucfirst($application->status ?? 'pending') }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($application->created_at)->format('M d, Y') }}</td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="#" class="view-btn" onclick="viewApplication({{ $application->id }}, '{{ $application->application_type }}'); return false;">View Details</a>
                                            @if(($application->status ?? 'pending') === 'confirmed')
                                                <span class="status-badge status-confirmed">✓ Confirmed</span>
                                                @if(in_array($application->application_type, ['Permit to Study', 'Study Leave']) && empty($application->signed_document_sent_at))
                                                    <button type="button" class="upload-btn" onclick="openUploadModal({{ $application->id }}, '{{ $appType }}', '{{ addslashes($application->full_name) }}')">Upload</button>
                                                @endif
                                            @else
                                                <form action="{{ url('/admin/applications/' . $application->id . '/confirm') }}" method="POST" class="action-form" onsubmit="return confirm('Confirm this application?');">
                                                    @csrf
                                                    <input type="hidden" name="type" value="{{ $appType }}">
                                                    <button type="submit" class="confirm-btn">Confirm</button>
                                                </form>
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
                        <h3>No applications in this folder</h3>
                        <p>Applications will appear here when they are submitted and (for confirmed folders) confirmed.</p>
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
            const fileLabelsByType = {
                'DENR Scholar': { 1: 'IPCR', 2: 'Invitation Letter', 3: 'Nomination Letter', 4: 'Service Record', 5: 'Certificate of No Pending Admin Case', 6: 'PDS w/ WES', 7: 'Self-Certification of Travel History', 8: 'Others' },
                'Study/Non-Study': { 1: 'IPCR', 2: 'Invitation Letter', 3: 'Nomination Letter', 4: 'Service Record', 5: 'Certificate of No Pending Admin Case', 6: 'PDS w/ WES', 7: 'Self-Certification of Travel History', 8: 'Others' },
                'Permit to Study': { 1: 'Request Letter', 2: 'IPCR', 3: 'Registration Form from School' },
                'Study Leave': { 1: 'Study Leave Memorandum', 2: 'Application for Leave', 3: 'Updated PDS', 4: 'Proof of Review', 5: 'Self-Review Certification', 6: 'Graduate Program Registration', 7: "Master's Completion Certification", 8: 'No Pending Case Certification', 9: "Supervisor's Certification" }
            };
            const fileLabels = fileLabelsByType[application.application_type] || fileLabelsByType['DENR Scholar'];
            const maxFiles = application.application_type === 'Permit to Study' ? 3 : (application.application_type === 'Study Leave' ? 9 : 8);
            let filesHtml = '';
            for (let i = 1; i <= maxFiles; i++) {
                const fileField = 'file_' + i;
                if (application[fileField]) {
                    filesHtml += '<div style="margin: 5px 0;"><a href="/storage/' + application[fileField] + '" target="_blank" class="file-link">' + (fileLabels[i] || 'File ' + i) + '</a></div>';
                }
            }
            let studyTypeHtml = application.application_type === 'Study/Non-Study' && application.study_type ? '<div style="margin-bottom: 15px;"><strong>Study Type:</strong> ' + application.study_type + '</div>' : '';
            document.getElementById('modalContent').innerHTML = '<div style="line-height: 1.8;">' +
                '<div style="margin-bottom: 15px;"><strong>Application Type:</strong> <span class="modal-type-badge">' + application.application_type + '</span></div>' + studyTypeHtml +
                '<div style="margin-bottom: 15px;"><strong>Full Name:</strong> ' + application.full_name + '</div>' +
                '<div style="margin-bottom: 15px;"><strong>Email:</strong> ' + application.email + '</div>' +
                '<div style="margin-bottom: 15px;"><strong>Position:</strong> ' + application.position + '</div>' +
                '<div style="margin-bottom: 15px;"><strong>Office:</strong> ' + application.office + '</div>' +
                '<div style="margin-bottom: 15px;"><strong>Submitted:</strong> ' + new Date(application.created_at).toLocaleString() + '</div>' +
                '<div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #f0f0f0;"><strong>Uploaded Files:</strong><div style="margin-top: 10px;">' + (filesHtml || 'No files uploaded') + '</div></div></div>';
            document.getElementById('applicationModal').style.display = 'block';
        }

        function closeModal() { document.getElementById('applicationModal').style.display = 'none'; }
        document.getElementById('applicationModal')?.addEventListener('click', function(e) { if (e.target === this) closeModal(); });

        function openUploadModal(appId, appType, applicantName) {
            document.getElementById('uploadModalApplicantName').textContent = 'Sending to: ' + (applicantName || 'Applicant');
            document.getElementById('uploadSignedForm').action = '/admin/applications/' + appId + '/upload-signed-document';
            document.getElementById('uploadFormType').value = appType;
            document.getElementById('signed_file').value = '';
            document.getElementById('upload_message').value = '';
            document.getElementById('uploadModal').style.display = 'block';
        }
        function closeUploadModal() { document.getElementById('uploadModal').style.display = 'none'; }
        document.getElementById('uploadModal')?.addEventListener('click', function(e) { if (e.target === this) closeUploadModal(); });

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
    </script>
</body>
</html>
