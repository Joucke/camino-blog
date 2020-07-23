<?php

use App\User;
use Illuminate\Support\Str;

it('connects to locationiq', function () {
    $response = $this->actingAs(factory(User::class)->create())
        ->get('/geocode-search?q=empire state building')
        ->assertOk()
        ->json();

    $this->assertTrue(count($response) > 0);

    $this->assertStringContainsString(
        'empire state building',
        Str::lower($response[0]['display_name']),
    );
})->group('integration-ext');
