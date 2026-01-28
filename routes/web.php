<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/admin_login', function () {
    return view('admin_login');
});

Route::get('/admin_home', function () {
    return view('admin_home');
});

// handle admin login post
Route::post('/admin_login', function (Request $request) {
    $request->validate([
        'login' => 'required|string',
        'password' => 'required',
    ]);

    $login = $request->input('login');

    // check normal email column OR the column named `username/email`
    $admin = DB::table('admin')
        ->where('email', $login)
        ->orWhereRaw('`username/email` = ?', [$login])
        ->first();

    if (! $admin) {
        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    $stored = $admin->password ?? null;

    $ok = ($stored && (Hash::check($request->password, $stored) || $request->password === $stored));

    if ($ok) {
        $request->session()->put('admin_id', $admin->id);
        return redirect('/admin_home');
    }

    return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
});

