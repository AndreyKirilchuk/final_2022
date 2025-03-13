<?php

namespace App\Http\Controllers;

use App\Models\ConsultantProblems;
use App\Models\Problem;
use Illuminate\Http\Request;

class ConsultantProblemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
           "problems" => "required|array",
           "problems.*" => "required|integer|exists:problems,id",
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $oldProblems = ConsultantProblems::where("user_id", auth()->id())->get();

        foreach($oldProblems as $oldProblem)
        {
            $oldProblem->delete();
        }

        foreach ($request->problems as $problem) {
            ConsultantProblems::create([
               "problem_id" => $problem,
               "user_id" => auth()->id()
            ]);
        }

        return $this->success(data: ["problems" => $request->problems]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsultantProblems $consultantProblems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsultantProblems $consultantProblems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConsultantProblems $consultantProblems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConsultantProblems $consultantProblems)
    {
        //
    }
}
