<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(data: ["regions" => Region::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = validator($request->all(), [
           "name" => "required|string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $region = Region::create($v->validated());

        return $this->success(data: ["region" => $region], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $v = validator($request->all(), [
            "name" => "string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $region->update($v->validated());

        return $this->success(data: ["region" => $region]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {

        $organization = Organization::where("region_id", $region->id)->first();

        if($organization)
        {
            return $this->errors(errors: ["region" => "Region have organizations"]);
        }

        $region->delete();

        return $this->success();
    }
}
