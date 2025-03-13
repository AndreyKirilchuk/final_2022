<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\Order;
use App\Models\Problem;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        return $this->success(data: ["problems" => Problem::where("category_id", $category->id)->get()]);
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
    public function store(Request $request, Category $category)
    {
        $v = validator($request->all(), [
            "title" => "required|string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $data = $v->validated();
        $data['category_id'] = $category->id;

        $problem = Problem::create($data);

        return $this->success(data: ["problem" => $problem], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Problem $problem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Problem $problem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category, Problem $problem)
    {
        $v = validator($request->all(), [
            "title" => "string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $problem->update($v->validated());

        return $this->success(data: ["problem" => $problem]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Problem $problem)
    {
        $application = Application::where('problem_id', $problem->id)->first();

        if($application)
        {
            return $this->errors(errors: "Problem have applications");
        }

        $problem->delete();

        return $this->success();
    }
}
