<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Problem;
use App\Models\Question;
use App\Models\Region;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(data: ["categories" => Category::all()]);
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
            "title" => "required|string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $category = Category::create($v->validated());

        return $this->success(data: ["category" => $category], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $v = validator($request->all(), [
            "title" => "string"
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $category->update($v->validated());

        return $this->success(data: ["category" => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $question = Problem::where('category_id', $category->id)->first();

        if($question)
        {
            return $this->errors(errors: ["category" => "Category have questions"]);
        }

        $category->delete();

        return $this->success();
    }
}
