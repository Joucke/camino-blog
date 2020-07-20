<?php

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('guests cannot upload photos', function () {
    $this->post('/photos', [])
        ->assertRedirect('/login');
});

test('users can upload photos', function () {
    Storage::fake('photos');

    $landscape = UploadedFile::fake()->image('landscape.jpg');
    $person = UploadedFile::fake()->image('person.jpg');

    $this->actingAs(factory(User::class)->create())
        ->json('POST', '/photos', [
            'photos' => [
                $landscape,
                $person
            ],
        ])
        ->assertOk();

    // Assert the file was stored...
    Storage::disk('photos')->assertExists(now()->format('Y-m-d') . '/' . $landscape->hashName());
    Storage::disk('photos')->assertExists(now()->format('Y-m-d') . '/' . $person->hashName());
});
