<?php

use App\Article;
use App\Photo;
use App\User;
use Illuminate\Http\Testing\File;

test('guests cannot delete photos', function () {
    $this->delete('/photos/1')
        ->assertRedirect('/login');

    $this->deleteJson('/photos/1')
        ->assertUnauthorized();
});

test('users can delete photos', function () {
    Storage::fake('photos');

    $path = File::image('foobar.jpg')->store(now()->format('Y-m-d'), 'photos');
    $photo = factory(Photo::class)->create(compact('path'));

    $this->actingAs(factory(User::class)->create())
        ->deleteJson('/photos/'.$photo->id)
        ->assertStatus(204);

    $path = File::image('foobar.jpg')->store(now()->format('Y-m-d'), 'photos');
    $photo = factory(Photo::class)->create(compact('path'));

    $this->actingAs(factory(User::class)->create())
        ->delete('/photos/'.$photo->id)
        ->assertRedirect('/');
});

test('deleting a photo object also removes the uploaded image', function () {
    Storage::fake('photos');

    $path = File::image('foobar.jpg')->store(now()->format('Y-m-d'), 'photos');

    $photo = factory(Photo::class)->create(compact('path'));

    $this->actingAs(factory(User::class)->create())
        ->deleteJson('/photos/'.$photo->id)
        ->assertStatus(204);

    Storage::disk('photos')->assertMissing($path);
});

test('only unused photos can be deleted', function () {
    Storage::fake('photos');

    $used = File::image('foobar.jpg')->store(now()->format('Y-m-d'), 'photos');
    $unused = File::image('foobar.jpg')->store(now()->format('Y-m-d'), 'photos');

    $usedPhoto = factory(Photo::class)->create([
        'path' => $used,
    ]);
    $unusedPhoto = factory(Photo::class)->create([
        'path' => $unused,
    ]);

    $article = factory(Article::class)->create([
        'body' => sprintf(
            '![foo](/images/%s)',
            $used
        )
    ]);

    $this->actingAs($article->author)
        ->deleteJson('/photos/'.$unusedPhoto->id)
        ->assertStatus(204);

    Storage::disk('photos')->assertMissing($unused);
    $this->assertCount(0, Photo::where('path', $unused)->get());

    $this->actingAs($article->author)
        ->deleteJson('/photos/'.$usedPhoto->id)
        ->assertForbidden()
        ;

    Storage::disk('photos')->assertExists($used);
    $this->assertCount(1, Photo::where('path', $used)->get());
});
