<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Show the application type selection page
     */
    public function showApplicationType()
    {
        return view('User.application_type');
    }

    /**
     * Show the application form based on selected type
     */
    public function showApplicationForm(Request $request)
    {
        $type = $request->query('type'); // Get type from URL parameter
        
        // Store the selected type in session
        if ($type) {
            $request->session()->put('application_type', $type);
        }
        
        // Show different forms based on application type
        switch ($type) {
            case 'denr_scholar':
                return view('User.denr_scholar_form');
            case 'study_non_study':
                return view('User.study_non_study_form');
            case 'permit_to_study':
                return view('User.permit_to_study_form');
            case 'study_leave':
                return view('User.study_leave_form');
            default:
                return redirect('/apply')->with('error', 'Invalid application type');
        }
    }

    /**
     * Handle DENR Scholar application form submission
     */
    public function submitDenrScholar(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'file1' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file2' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file3' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file4' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file5' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file6' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file7' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file8' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        try {
            // Create directory for storing application files if it doesn't exist
            $storagePath = 'applications/denr_scholar';
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            // Handle file uploads and store file paths
            $filePaths = [];
            for ($i = 1; $i <= 8; $i++) {
                if ($request->hasFile("file{$i}")) {
                    $file = $request->file("file{$i}");
                    $fileName = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($storagePath, $fileName, 'public');
                    $filePaths["file_{$i}"] = $filePath;
                }
            }

            // Clean phone number (remove non-numeric characters)
            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phonenumber);
            
            // Convert to integer (now using bigint, so no size limit issues)
            $phoneNumberInt = (int)$phoneNumber;

            // Insert data into denr_scholar table
            DB::table('denr_scholar')->insert([
                'full_name' => $request->fullname,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'position' => $request->position,
                'office' => $request->office,
                'phone_number' => $phoneNumberInt,
                'file_1' => $filePaths['file_1'] ?? null,
                'file_2' => $filePaths['file_2'] ?? null,
                'file_3' => $filePaths['file_3'] ?? null,
                'file_4' => $filePaths['file_4'] ?? null,
                'file_5' => $filePaths['file_5'] ?? null,
                'file_6' => $filePaths['file_6'] ?? null,
                'file_7' => $filePaths['file_7'] ?? null,
                'file_8' => $filePaths['file_8'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/apply')->with('success', 'Your DENR Scholar application has been submitted successfully!');
        } catch (\Exception $e) {
            // Log the error and return with error message
            \Log::error('Error submitting DENR Scholar application: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    /* Handle Study / Non-Study application form submission */
    public function submitStudyNonStudy(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'study_type' => 'required|string|in:Study,Non-Study',
            'file1' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file2' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file3' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file4' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file5' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file6' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file7' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file8' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        try {
            // Create directory for storing application files if it doesn't exist
            $storagePath = 'applications/study_non_study';
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            // Handle file uploads and store file paths
            $filePaths = [];
            for ($i = 1; $i <= 8; $i++) {
                if ($request->hasFile("file{$i}")) {
                    $file = $request->file("file{$i}");
                    $fileName = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($storagePath, $fileName, 'public');
                    $filePaths["file_{$i}"] = $filePath;
                }
            }

            // Clean phone number (remove non-numeric characters)
            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phonenumber);
            
            // Convert to integer (now using bigint, so no size limit issues)
            $phoneNumberInt = (int)$phoneNumber;

            // Insert data into study_non_study table
            DB::table('study_non_study')->insert([
                'full_name' => $request->fullname,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'position' => $request->position,
                'office' => $request->office,
                'phone_number' => $phoneNumberInt,
                'study_type' => $request->study_type,
                'file_1' => $filePaths['file_1'] ?? null,
                'file_2' => $filePaths['file_2'] ?? null,
                'file_3' => $filePaths['file_3'] ?? null,
                'file_4' => $filePaths['file_4'] ?? null,
                'file_5' => $filePaths['file_5'] ?? null,
                'file_6' => $filePaths['file_6'] ?? null,
                'file_7' => $filePaths['file_7'] ?? null,
                'file_8' => $filePaths['file_8'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/apply')->with('success', 'Your Study / Non-Study application has been submitted successfully!');
        } catch (\Exception $e) {
            // Log the error and return with error message
            \Log::error('Error submitting Study / Non-Study application: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    public function submitPermitToStudy(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
            'file1' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file2' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'file3' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $storagePath = 'applications/permit_to_study';
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            $filePaths = [];
            for ($i = 1; $i <= 3; $i++) {
                if ($request->hasFile("file{$i}")) {
                    $file = $request->file("file{$i}");
                    $fileName = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($storagePath, $fileName, 'public');
                    $filePaths["file_{$i}"] = $filePath;
                }
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phonenumber);
            
            $phoneNumberInt = (int)$phoneNumber;

            DB::table('permit_to_study')->insert([
                'full_name' => $request->fullname,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'position' => $request->position,
                'office' => $request->office,
                'phone_number' => $phoneNumberInt,
                'file_1' => $filePaths['file_1'] ?? null,
                'file_2' => $filePaths['file_2'] ?? null,
                'file_3' => $filePaths['file_3'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/apply')->with('success', 'Your Permit to Study application has been submitted successfully!');
        } catch (\Exception $e) {
            // Log the error and return with error message
            \Log::error('Error submitting Permit to Study application: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }

    public function submitStudyLeave(Request $request)
    {
        $rules = [
            'fullname' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'office' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:20',
        ];
        for ($i = 1; $i <= 9; $i++) {
            $rules["file{$i}"] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240';
        }
        $request->validate($rules);

        try {
            $storagePath = 'applications/study_leave';
            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->makeDirectory($storagePath);
            }

            $filePaths = [];
            for ($i = 1; $i <= 9; $i++) {
                if ($request->hasFile("file{$i}")) {
                    $file = $request->file("file{$i}");
                    $fileName = time() . '_' . $i . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs($storagePath, $fileName, 'public');
                    $filePaths["file_{$i}"] = $filePath;
                }
            }

            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phonenumber);
            $phoneNumberInt = (int) $phoneNumber;

            $insert = [
                'full_name' => $request->fullname,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'position' => $request->position,
                'office' => $request->office,
                'phone_number' => $phoneNumberInt,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            for ($i = 1; $i <= 9; $i++) {
                $insert["file_{$i}"] = $filePaths["file_{$i}"] ?? null;
            }
            DB::table('study_leave')->insert($insert);

            return redirect('/apply')->with('success', 'Your Study Leave application has been submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error submitting Study Leave application: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }
}
