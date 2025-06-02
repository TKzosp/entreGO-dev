<?php

use App\Models\Usuario;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('guests are redirected to the login page', function () {
    $response = $this->get('/teste');
    $response->assertRedirect('/login');
});

test('authenticated usuarios can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/teste');
    $response->assertStatus(200);
});