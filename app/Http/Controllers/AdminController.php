<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationConfirmed;

class AdminController extends Controller
{
    /**
     * Show the admin login page
     */
    public function showLogin()
    {
        return view('Admin.admin_login');
    }

    /**
     * Show the admin home page with dashboard of applications
     */
    public function showHome(Request $request)
    {
        // Check if admin is logged in
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $filter = $request->query('filter');
        $applications = $this->getAllApplications(null, $filter);
        $confirmedApplications = $this->getAllConfirmedApplications();

        $data = [
            'applications' => $applications,
            'confirmedApplications' => $confirmedApplications
        ];

        if ($filter) {
            $data['active_filter'] = $filter;
            $filterTitles = [
                'denr_scholar' => 'DENR Scholar Applications',
                'study' => 'Study Applications',
                'non_study' => 'Non-Study Applications',
                'permit_to_study' => 'Permit to Study Applications',
                'study_leave' => 'Study Leave Applications',
            ];
            $data['section_title'] = $filterTitles[$filter] ?? 'All Applications';
            $data['show_filter_link'] = true;
        }

        return view('Admin.admin_home', $data);
    }

    private function getAllApplications($whereClause = null, $typeFilter = null, $includeAllConfirmed = false)
    {
        $oneWeekAgo = \Carbon\Carbon::now()->subWeek();

        $denrQuery = DB::table('denr_scholar')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number', 
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("NULL as file_9, 'DENR Scholar' as application_type, NULL as study_type")
            ->where(function($query) use ($oneWeekAgo, $includeAllConfirmed) {
                // Show pending applications OR confirmed within the last week (or all if includeAllConfirmed)
                $query->where('status', 'pending')
                      ->orWhere(function($q) use ($oneWeekAgo, $includeAllConfirmed) {
                          $q->where('status', 'confirmed');
                          if (!$includeAllConfirmed) {
                              $q->where('confirmed_at', '>=', $oneWeekAgo);
                          }
                      });
            });

        $studyQuery = DB::table('study_non_study')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("NULL as file_9, 'Study/Non-Study' as application_type, study_type")
            ->where(function($query) use ($oneWeekAgo, $includeAllConfirmed) {
                $query->where('status', 'pending')
                      ->orWhere(function($q) use ($oneWeekAgo, $includeAllConfirmed) {
                          $q->where('status', 'confirmed');
                          if (!$includeAllConfirmed) {
                              $q->where('confirmed_at', '>=', $oneWeekAgo);
                          }
                      });
            });

        $permitQuery = DB::table('permit_to_study')
            ->selectRaw("id, full_name, age, gender, email, position, office, phone_number, file_1, file_2, file_3, NULL as file_4, NULL as file_5, NULL as file_6, NULL as file_7, NULL as file_8, NULL as file_9, status, confirmed_at, created_at, updated_at, 'Permit to Study' as application_type, NULL as study_type")
            ->where(function($query) use ($oneWeekAgo, $includeAllConfirmed) {
                $query->where('status', 'pending')
                      ->orWhere(function($q) use ($oneWeekAgo, $includeAllConfirmed) {
                          $q->where('status', 'confirmed');
                          if (!$includeAllConfirmed) {
                              $q->where('confirmed_at', '>=', $oneWeekAgo);
                          }
                      });
            });

        $studyLeaveQuery = DB::table('study_leave')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("file_9, 'Study Leave' as application_type, NULL as study_type")
            ->where(function($query) use ($oneWeekAgo, $includeAllConfirmed) {
                $query->where('status', 'pending')
                      ->orWhere(function($q) use ($oneWeekAgo, $includeAllConfirmed) {
                          $q->where('status', 'confirmed');
                          if (!$includeAllConfirmed) {
                              $q->where('confirmed_at', '>=', $oneWeekAgo);
                          }
                      });
            });

        if ($whereClause) {
            $denrQuery->where('created_at', '>=', $whereClause);
            $studyQuery->where('created_at', '>=', $whereClause);
            $permitQuery->where('created_at', '>=', $whereClause);
            $studyLeaveQuery->where('created_at', '>=', $whereClause);
        }

        // Apply type filter
        if ($typeFilter) {
            switch ($typeFilter) {
                case 'denr_scholar':
                    return $denrQuery->orderBy('created_at', 'desc')->get();
                case 'study':
                    return $studyQuery->where('study_type', 'Study')
                        ->orderBy('created_at', 'desc')->get();
                case 'non_study':
                    return $studyQuery->where('study_type', 'Non-Study')
                        ->orderBy('created_at', 'desc')->get();
                case 'permit_to_study':
                    return $permitQuery->orderBy('created_at', 'desc')->get();
                case 'study_leave':
                    return $studyLeaveQuery->orderBy('created_at', 'desc')->get();
            }
        }

        return $denrQuery->union($studyQuery)->union($permitQuery)->union($studyLeaveQuery)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all confirmed applications (for folder view - no time limit)
     */
    private function getAllConfirmedApplications()
    {
        $denrQuery = DB::table('denr_scholar')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number', 
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("NULL as file_9, 'DENR Scholar' as application_type, NULL as study_type")
            ->where('status', 'confirmed');

        $studyQuery = DB::table('study_non_study')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("NULL as file_9, 'Study/Non-Study' as application_type, study_type")
            ->where('status', 'confirmed');

        $permitQuery = DB::table('permit_to_study')
            ->selectRaw("id, full_name, age, gender, email, position, office, phone_number, file_1, file_2, file_3, NULL as file_4, NULL as file_5, NULL as file_6, NULL as file_7, NULL as file_8, NULL as file_9, status, confirmed_at, created_at, updated_at, 'Permit to Study' as application_type, NULL as study_type")
            ->where('status', 'confirmed');

        $studyLeaveQuery = DB::table('study_leave')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6', 'file_7', 'file_8', 'status', 'confirmed_at', 'created_at', 'updated_at')
            ->selectRaw("file_9, 'Study Leave' as application_type, NULL as study_type")
            ->where('status', 'confirmed');

        return $denrQuery->union($studyQuery)->union($permitQuery)->union($studyLeaveQuery)
            ->orderBy('confirmed_at', 'desc')
            ->get();
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required',
        ]);

        $login = $request->input('login');

        // check the column named `username/email` (using whereRaw to handle the slash in column name)
        $admin = DB::table('admin')
            ->whereRaw('`username/email` = ?', [$login])
            ->first();

        if (!$admin) {
            return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
        }

        $stored = $admin->password ?? null;

        // Check password - handle both hashed and plain text passwords
        $ok = false;
        if ($stored) {
            // Check if password is hashed (Bcrypt hashes start with $2y$)
            if (strpos($stored, '$2y$') === 0 || strpos($stored, '$2a$') === 0 || strpos($stored, '$2b$') === 0) {
                // Password is hashed, use Hash::check
                $ok = Hash::check($request->password, $stored);
            } else {
                // Password is plain text, do direct comparison
                $ok = ($request->password === $stored);
            }
        }

        if ($ok) {
            $request->session()->put('admin_id', $admin->id);
            return redirect('/admin_home');
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    /**
     * Confirm an application - move files to confirmed folder and update status
     */
    public function confirmApplication(Request $request, $id)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $type = $request->input('type', 'denr_scholar');
        $tableMap = [
            'denr_scholar' => 'denr_scholar',
            'study_non_study' => 'study_non_study',
            'permit_to_study' => 'permit_to_study',
            'study_leave' => 'study_leave',
        ];
        $table = $tableMap[$type] ?? 'denr_scholar';

        // Folder names for confirmed applications
        $folderMap = [
            'denr_scholar' => 'DENR Scholar',
            'study_non_study' => 'Study-Non-Study',
            'permit_to_study' => 'Permit to Study',
            'study_leave' => 'Study Leave',
        ];
        $folderName = $folderMap[$type] ?? 'DENR Scholar';

        $application = DB::table($table)->where('id', $id)->first();
        if (!$application) {
            return redirect('/admin_home')->with('error', 'Application not found.');
        }

        if ($application->status === 'confirmed') {
            return redirect('/admin_home')->with('error', 'Application is already confirmed.');
        }

        try {
            // Create confirmed folder structure if it doesn't exist
            $confirmedPath = "confirmed/{$folderName}";
            if (!Storage::disk('public')->exists($confirmedPath)) {
                Storage::disk('public')->makeDirectory($confirmedPath);
            }

            // Move files to confirmed folder (permit_to_study: 3, study_leave: 9, others: 8)
            $maxFiles = $type === 'permit_to_study' ? 3 : ($type === 'study_leave' ? 9 : 8);
            $newFilePaths = [];
            
            for ($i = 1; $i <= $maxFiles; $i++) {
                $col = "file_{$i}";
                if (!empty($application->$col) && Storage::disk('public')->exists($application->$col)) {
                    $oldPath = $application->$col;
                    $fileName = basename($oldPath);
                    $newPath = "{$confirmedPath}/{$fileName}";
                    
                    // Move the file
                    Storage::disk('public')->move($oldPath, $newPath);
                    $newFilePaths[$col] = $newPath;
                }
            }

            // Update the application status, confirmed_at timestamp, and file paths
            $updateData = [
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'updated_at' => now()
            ];
            foreach ($newFilePaths as $col => $path) {
                $updateData[$col] = $path;
            }
            
            DB::table($table)->where('id', $id)->update($updateData);

            // Send confirmation email to applicant
            $applicationTypeNames = [
                'denr_scholar' => 'DENR Scholar',
                'study_non_study' => 'Study / Non-Study',
                'permit_to_study' => 'Permit to Study',
                'study_leave' => 'Study Leave',
            ];
            $applicationTypeName = $applicationTypeNames[$type] ?? 'Application';
            
            try {
                Mail::to($application->email)->send(new ApplicationConfirmed($application, $applicationTypeName));
                \Log::info('Confirmation email sent to: ' . $application->email);
            } catch (\Exception $mailError) {
                \Log::error('Failed to send confirmation email: ' . $mailError->getMessage());
                // Don't fail the whole operation if email fails
            }

            return redirect('/admin_home')->with('success', 'Application confirmed successfully! Confirmation email sent to ' . $application->email);
        } catch (\Exception $e) {
            \Log::error('Error confirming application: ' . $e->getMessage());
            return redirect('/admin_home')->with('error', 'An error occurred while confirming the application.');
        }
    }

    /**
     * Delete an application
     */
    public function deleteApplication(Request $request, $id)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $type = $request->input('type', 'denr_scholar');
        $tableMap = [
            'denr_scholar' => 'denr_scholar',
            'study_non_study' => 'study_non_study',
            'permit_to_study' => 'permit_to_study',
            'study_leave' => 'study_leave',
        ];
        $table = $tableMap[$type] ?? 'denr_scholar';

        $application = DB::table($table)->where('id', $id)->first();
        if (!$application) {
            return redirect('/admin_home')->with('error', 'Application not found.');
        }

        $maxFiles = $type === 'permit_to_study' ? 3 : ($type === 'study_leave' ? 9 : 8);
        // Delete stored files if they exist
        for ($i = 1; $i <= $maxFiles; $i++) {
            $col = "file_{$i}";
            if (!empty($application->$col) && Storage::disk('public')->exists($application->$col)) {
                Storage::disk('public')->delete($application->$col);
            }
        }

        DB::table($table)->where('id', $id)->delete();

        return redirect('/admin_home')->with('success', 'Application deleted successfully.');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget('admin_id');
        return redirect('/home')->with('success', 'You have been logged out successfully');
    }

    public function viewTodaysApplications(Request $request)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $applications = $this->getAllApplications(\Carbon\Carbon::today());
        $confirmedApplications = $this->getAllConfirmedApplications();

        return view('Admin.admin_home', [
            'applications' => $applications,
            'confirmedApplications' => $confirmedApplications,
            'section_title' => "Today's Applications",
            'show_filter_link' => true,
        ]);
    }

    public function viewThisWeekApplications(Request $request)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $applications = $this->getAllApplications(\Carbon\Carbon::now()->startOfWeek());
        $confirmedApplications = $this->getAllConfirmedApplications();

        return view('Admin.admin_home', [
            'applications' => $applications,
            'confirmedApplications' => $confirmedApplications,
            'section_title' => "This Week's Applications",
            'show_filter_link' => true,
        ]);
    }

    public function viewThisMonthApplications(Request $request)
    {
        if (!$request->session()->has('admin_id')) {
            return redirect('/admin_login')->with('error', 'Please login first');
        }

        $applications = $this->getAllApplications(\Carbon\Carbon::now()->startOfMonth());
        $confirmedApplications = $this->getAllConfirmedApplications();

        return view('Admin.admin_home', [
            'applications' => $applications,
            'confirmedApplications' => $confirmedApplications,
            'section_title' => "This Month's Applications",
            'show_filter_link' => true,
        ]);
    }

    public function getApplicationsJson(Request $request)
    {
        if (!$request->session()->has('admin_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $filter = $request->query('filter');
        $applications = $this->getAllApplications(null, $filter);
        $confirmedApplications = $this->getAllConfirmedApplications();

        // Calculate stats
        $today = \Carbon\Carbon::today();
        $weekStart = \Carbon\Carbon::now()->startOfWeek();
        $monthStart = \Carbon\Carbon::now()->startOfMonth();

        $stats = [
            'total' => $applications->count(),
            'today' => $applications->filter(fn($app) => \Carbon\Carbon::parse($app->created_at) >= $today)->count(),
            'week' => $applications->filter(fn($app) => \Carbon\Carbon::parse($app->created_at) >= $weekStart)->count(),
            'month' => $applications->filter(fn($app) => \Carbon\Carbon::parse($app->created_at) >= $monthStart)->count(),
        ];

        return response()->json([
            'applications' => $applications,
            'confirmedApplications' => $confirmedApplications,
            'stats' => $stats,
        ]);
    }
}
