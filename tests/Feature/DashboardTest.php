<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_workspace_sections_render_the_dashboard_page(): void
    {
        $sections = [
            '/' => 'dashboard',
            '/campaigns' => 'campaigns',
            '/contacts' => 'contacts',
            '/templates' => 'templates',
            '/analytics' => 'analytics',
            '/settings' => 'settings',
        ];

        foreach ($sections as $path => $section) {
            $this->get($path)
                ->assertInertia(fn (Assert $page) => $page
                    ->component('Dashboard')
                    ->where('section', $section));
        }
    }
}
