<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', DashboardController::class)
    ->defaults('section', 'dashboard')
    ->name('dashboard');

Route::get('/campaigns', DashboardController::class)
    ->defaults('section', 'campaigns')
    ->name('campaigns.index');

Route::get('/contacts', DashboardController::class)
    ->defaults('section', 'contacts')
    ->name('contacts.index');

Route::get('/templates', DashboardController::class)
    ->defaults('section', 'templates')
    ->name('templates.index');

Route::get('/analytics', DashboardController::class)
    ->defaults('section', 'analytics')
    ->name('analytics.index');

Route::get('/settings', DashboardController::class)
    ->defaults('section', 'settings')
    ->name('settings.index');
