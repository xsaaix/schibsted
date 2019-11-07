<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GitHubRepositoryControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCompareRepositoriesByNames()
    {
        $response = $this->json('GET', '/api/repository/compare/name/Laravel-Github/test');

        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                'name' => 'Laravel-GitHub'
                ]
            );
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCompareRepositoriesByUsersAndNames()
    {
        $response = $this->json('GET', '/api/repository/compare/user/GrahamCampbell/laravel/name/Laravel-Github/laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                'name' => 'Laravel-GitHub',
                'name' => 'laravel'
                ]
            );
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCompareRepositoriesByURLs()
    {
        $response = $this->json('GET', '/api/repository/compare/url/?url1=https://github.com/GrahamCampbell/Laravel-GitHub&url2=https://github.com/laravel/laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                'name' => 'Laravel-GitHub',
                'name' => 'laravel'
                ]
            );
    }
}
