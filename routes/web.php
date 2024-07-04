<?php

use App\Http\Controllers\HooksController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});