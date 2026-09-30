<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('notes', NoteController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
Route::post('notes/{note}/file',    [NoteController::class, 'uploadFile']);
Route::post('notes/{id}/restore',   [NoteController::class, 'restore']);
Route::delete('notes/{id}/force',   [NoteController::class, 'forceDelete']);
