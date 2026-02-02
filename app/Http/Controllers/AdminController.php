<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        $data = [
            'applications' => $applications
        ];

        if ($filter) {
            $data['active_filter'] = $filter;
            $filterTitles = [
                'denr_scholar' => 'DENR Scholar Applications',
                'study' => 'Study Applications',
                'non_study' => 'Non-Study Applications',
                'permit_to_study' => 'Permit to Study Applications',
            ];
            $data['section_title'] = $filterTitles[$filter] ?? 'All Applications';
            $data['show_filter_link'] = true;
        }

        return view('Admin.admin_home', $data);
    }

    private function getAllApplications($whereClause = null, $typeFilter = null)
    {
        $denrQuery = DB::table('denr_scholar')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number', 
                     'file_1', 'file_2', 'file_3', 'created_at', 'updated_at')
            ->selectRaw("'DENR Scholar' as application_type, NULL as study_type");

        $studyQuery = DB::table('study_non_study')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'created_at', 'updated_at')
            ->selectRaw("'Study/Non-Study' as application_type, study_type");

        $permitQuery = DB::table('permit_to_study')
            ->select('id', 'full_name', 'age', 'gender', 'email', 'position', 'office', 'phone_number',
                     'file_1', 'file_2', 'file_3', 'created_at', 'updated_at')
            ->selectRaw("'Permit to Study' as application_type, NULL as study_type");

        if ($whereClause) {
            $denrQuery->where('created_at', '>=', $whereClause);
            $studyQuery->where('created_at', '>=', $whereClause);
            $permitQuery->where('created_at', '>=', $whereClause);
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
            }
        }

        return $denrQuery->union($studyQuery)->union($permitQuery)
            ->orderBy('created_at', 'desc')
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
        ];
        $table = $tableMap[$type] ?? 'denr_scholar';

        $application = DB::table($table)->where('id', $id)->first();
        if (!$application) {
            return redirect('/admin_home')->with('error', 'Application not found.');
        }

        // Delete stored files if they exist
        for ($i = 1; $i <= 8; $i++) {
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

        return view('Admin.admin_home', [
            'applications' => $applications,
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

        return view('Admin.admin_home', [
            'applications' => $applications,
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

        return view('Admin.admin_home', [
            'applications' => $applications,
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
            'stats' => $stats,
        ]);
    }
}
