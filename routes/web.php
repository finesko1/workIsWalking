<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Возвращает главный шаблон SPA
})->where('any', '.*');

// Обработчик посторонних URL

Route::get('{path}', function() {
    return view('spa'); // Single page application
})->where('path', '.*');
