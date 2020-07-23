<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
    public function store(Request $request)
    {
        $attributes = $request->only(['title', 'latitude', 'longitude']);
        Location::create($attributes);

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

        return view('locations.show', compact('location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $location->update($request->only(['title', 'latitude', 'longitude']));

        if ($request->wantsJson()) {
            return $location;
        }

        return redirect('/locations/'.$location->id);
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
