<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Tests\TestCase;


class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(\App\User::class)->create([
            'username' => 'test-admin',
            'email' => 'test-admin@example.org',
            'is_admin' => TRUE
        ]);
    }

    /**
     * See where a logged-in user can access their Org page
     *
     * @return void
     */
    public function testLoggedIn()
    {

        $user = \App\User::firstWhere('username', 'test-admin');
        $this->actingAs($user)
            ->get('/admin/organization')
            ->assertSuccessful();
    }

    /**
     * Make sure a non-authenticated user cannot access the Org page
     *
     * @return void
     */
    public function testNotLoggedIn()
    {
        $this->get('/admin/organization')
            ->assertRedirect('/login');
    }
}
