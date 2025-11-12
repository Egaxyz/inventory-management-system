<?php

use Illuminate\Support\Facades\Route;


Route::get('/{any}', function () {
    return view('welcome'); // nama blade utama kamu
})->where('any', '.*');