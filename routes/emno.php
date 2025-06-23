<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('emno/home');
})->name('home');

Route::get('/home', function () {
    return view('emno/home');
})->name('home');
