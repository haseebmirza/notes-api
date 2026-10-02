<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'api'  => 'Notes API',
    'docs' => '/api-docs',
]));

Route::view('/api-docs', 'api-docs');
