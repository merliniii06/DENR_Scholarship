<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/admin_login', function () {
    return view('admin_login');
});

Route::get('/apply', function () {
    return view('apply');
});

