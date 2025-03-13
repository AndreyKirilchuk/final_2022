<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region, Organization $organization)
    {
        return $this->success(data: ["consultants" => User::where("organization_id", $organization->id)->get()]);
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
    public function store(Request $request, Region $region, Organization $organization)
    {
        $v = validator($request->all(), [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => [
                "required",
                "string",
                "min:8",
                "regex:/[A-Za-z\d]+$/",
                "regex:/[A-Za-z]{1}/",
                "regex:/[\d]{1}/",
            ]
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        if($organization->region_id != $region->id)
        {
            return $this->errors(errors: "Bad region id!");
        }

        $data = $v->validated();
        $data["region_id"] = $region->id;
        $data["organization_id"] = $organization->id;

        $item = User::create($data);

        return $this->success(data: ["consultant" => $item], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region, Organization $organization, User $user)
    {
        if($user->role === "ADMIN")
        {
            return $this->errors(errors: "Forbidden!", code: 403);
        }

        $v = validator($request->all(), [
            "first_name" => "string",
            "last_name" => "string",
            "email" => "email|unique:users,email",
            "password" => "string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $user->update($v->validated());

        return $this->success(data: ["consultant" => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, Organization $organization, User $user)
    {
        if($user->role === "ADMIN")
        {
            return $this->errors(errors: "Forbidden!", code: 403);
        }

        $user->delete();

        return $this->success();
    }
}
