<?php

use App\Location;

it('gets an url attribute', function () {
    $location = factory(Location::class)->create(['title' => 'Somewhere']);

    $this->assertSame('/locations/somewhere', $location->url);
});
