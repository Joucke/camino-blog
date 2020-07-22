<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    protected string $token;

    public function __construct()
    {
        $this->token = config('locationiq.token');
    }

    public function search(Request $request)
    {
        $url = sprintf(
            'https://eu1.locationiq.com/v1/search.php?key=%s&q=%s&format=json',
            $this->token,
            $request->input('q')
        );

        return Http::get($url)
            ->json();
    }

    public function reverse(Request $request)
    {
        $url = sprintf(
            'https://eu1.locationiq.com/v1/reverse.php?key=%s&lat=%s&lon=%s&format=json',
            $this->token,
            $request->input('lat'),
            $request->input('lng'),
        );

        return Http::get($url)
            ->json();
    }
}
