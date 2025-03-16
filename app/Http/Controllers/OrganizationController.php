<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        return $this->success(data: ["organizations" => Organization::where("region_id", $region->id)->get()]);
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
    public function store(Request $request, Region $region)
    {
        $v = validator($request->all(), [
            "name" => "required|string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $data = $v->validated();
        $data["region_id"] = $region->id;

        $item = Organization::create($data);

        return $this->success(data: ["organization" => $item], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Region $region, Organization $organization)
    {
        $v = validator($request->all(), [
            "name" => "string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $organization->update($v->validated());

        return $this->success(data: ["organization" => $organization]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Organization $organization)
    {
        $consultans = User::where("organization_id", $organization->id)->first();

        if($consultans)
        {
            return $this->errors(errors: ["organization" => "Organization have consultants"]);
        }

        $organization->delete();

        return $this->success();
    }
}
