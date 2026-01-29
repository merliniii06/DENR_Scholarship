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

        // Fetch all applications from employee table
        $applications = DB::table('employee')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin.admin_home', [
            'applications' => $applications
        ]);
    }

    /**
     * Handle admin login form submission
     */
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

        $application = DB::table('employee')->where('id', $id)->first();
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

        DB::table('employee')->where('id', $id)->delete();

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

        $applications = DB::table('employee')
            ->where('created_at', '>=', \Carbon\Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

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

        $applications = DB::table('employee')
            ->where('created_at', '>=', \Carbon\Carbon::now()->startOfWeek())
            ->orderBy('created_at', 'desc')
            ->get();

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

        $applications = DB::table('employee')
            ->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Admin.admin_home', [
            'applications' => $applications,
            'section_title' => "This Month's Applications",
            'show_filter_link' => true,
        ]);
    }
}
