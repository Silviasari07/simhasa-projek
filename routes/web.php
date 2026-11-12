<?php

use App\Http\Controllers\HamaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('hama', HamaController::class);