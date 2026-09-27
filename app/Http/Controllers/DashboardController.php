<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(string $section = 'dashboard'): Response
    {
        return Inertia::render('Dashboard', [
            'section' => $section,
        ]);
    }
}
