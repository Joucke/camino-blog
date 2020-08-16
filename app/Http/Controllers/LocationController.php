<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocation;
use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locations = Location::latest()->get();

        if ($request->wantsJson()) {
            return $locations;
        }

        return view('locations.index', compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocation $request)
    {
        Location::create($request->validated());

        return Location::latest()->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Location $location)
    {
        if ($request->wantsJson()) {
            return $location;
        }

        $articles = $location->articles()->forIndex();
        return (new ArticleController)->index($request, $articles, $location);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     *
     * @return \Illuminate\Http\Response
     */
    public function update(StoreLocation $request, Location $location)
    {
        $location->update($request->validated());

        if ($request->wantsJson()) {
            if ($request->has('pivot')) {
                $location->pivot = $request->input('pivot');
            }
            return $location;
        }

        return redirect($location->url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Location $location)
    {
        if ($location->articles->isEmpty()) {
            $location->delete();
        } else {
            // TODO: move to translation file
            $request->session()->flash('error', 'Een locatie kan alleen verwijderd worden wanneer deze niet aan een of meer blogs is gekoppeld.');
        }
        return redirect('/locations');
    }
}
