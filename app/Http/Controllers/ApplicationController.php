<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Category;
use App\Models\ConsultantProblems;
use App\Models\Organization;
use App\Models\Problem;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

//        $query = Application::query()
//        ->whereBetween('date', [Carbon::parse($request->dateFrom)->format('Y-m-d') ?? '9999-99-99', Carbon::parse($request->dateTo)->format('Y-m-d') ?? '9999-99-99']);

        $query = Application::query();

        if($request->dateFrom)
        {
            $query->whereDate('created_at', '>=', Carbon::parse($request->dateFrom)->format('Y-m-d H:i:s'));
        }

        if($request->dateTo)
        {
            $query->whereDate('date', '<=', Carbon::parse($request->dateTo)->format('Y-m-d'));
        }

        if($request->region_id)
        {
            $query->where('region_id', $request->region_id);
        }

        if($request->category_id)
        {
            $query->where('category_id', $request->category_id);
        }

        if($request->organization_id)
        {
            $query->where('organization_id', $request->organization_id);
        }

        if($request->consultant_id)
        {
            $query->where('consultant_id', $request->consultant_id);
        }

        if($request->category_id)
        {
            $query->where('category_id', $request->category_id);
        }

        if($request->problem_id)
        {
            $query->where('problem_id', $request->problem_id);
        }

        if($request->status)
        {
            $query->where('status', $request->status);
        }

        if($user->role !== "ADMIN")
        {
            $query->where("consultant_id", $user->id);
        }

        return $this->success(data: ["consultations" => ApplicationResource::collection($query->get())]);
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
           "firstname" => "required|string",
           "lastname" => "required|string",
           "email" => "required|email",
           "tel" => "required|string",
           "kid" => "required|string",
           "age" => "required|integer",
           "region_id" => "required|integer|exists:regions,id",
           "organization_id" => "required|integer|exists:organizations,id",
           "category_id" => "required|integer|exists:categories,id",
           "problem_id" => "required|integer|exists:problems,id",
           "consultant_id" => "required|integer|exists:users,id",
           "date" => "required|date|date_format:Y-m-d|after_or_equal:today",
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $consultant = User::find($request->consultant_id);

        if($consultant->role !== "CONSULTANT")
        {
            return $this->errors(errors:["consultant" => "Consultant not found"] );
        }

        if($consultant->region_id != $request->region_id)
        {
            return $this->errors(errors: ["region" => "Region not found"]);
        }

        if($consultant->organization_id != $request->organization_id)
        {
            return $this->errors(errors: ["organization" => "Organization not found"]);
        }

        $problem = Problem::find($request->problem_id);

        if($problem->category_id != $request->category_id)
        {
            return $this->errors(errors: ["category" => "Category not found"]);
        }

        $thisProblem = ConsultantProblems::where(["problem_id" => $request->problem_id, "user_id" => $request->consultant_id])->first();

        if(!$thisProblem)
        {
            return $this->errors(errors: ["problem" => "This consultant dont working this problem"]);
        }

        $data = $v->validated();
        $data["code"] = rand(000000, 999999);

        $application = Application::create($data);

        $region = Region::find($request->region_id);
        $organization = Region::find($request->organization_id);
        $category = Category::find($request->category_id);
        $problem = Category::find($request->problem_id);


        return $this->success(data: [
            "consultation" =>  $application,
            "region" => $region,
            "organization" => $organization,
            "category" => $category,
            "problem" => $problem,
        ], code: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        return $this->success(data: ["consultations" => $application]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    public function sendRating($request)
    {
        $v = validator($request->all(), [
            "email" => "required|email",
            "code" => "required|integer",
            "rating" => "required|integer|between:1,5",
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $application = Application::where(["email" => $request->email, 'code' => $request->code])->firstOrFail();

        if($application->status !== "hoodwink")
        {
            return $this->errors(errors: "Application status dont advice", code: 403);
        }

        $application->update(["rating" => $request->rating]);

        return $this->success(data: ["consultation" => $application]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $token = $request->bearerToken();

        if(!$token)
        {
            return $this->sendRating($request);
        }

        $user = User::where('token', $token)->first();

        if(!$user)
        {
            return $this->sendRating($request);
        }

        $v = validator($request->all(), [
            "status" => "required|string|in:rejection,accepted,hoodwink",
        ]);

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $status = $request->status;

        if($request->status === "rejection")
        {
            $v = validator($request->all(), [
                "rejection" => "required|string",
            ]);
        }

        if($request->status === "accepted")
        {
            $v = validator($request->all(), [
                "consDate" => "required|date|date_format:Y-m-d|after_or_equal:" . $application->date,
            ]);
        }

        if($request->status === "hoodwink")
        {
            $v = validator($request->all(), [
                "result" => "required|string",
                "advice" => "required|string",
            ]);
        }

        if($v->fails())
        {
            return $this->errors(errors: $v->errors());
        }

        $application->update(["status" => $status]);

        if($request->status === "rejection") $application->update(["rejection" => $request->rejection]);

        if($request->status === "accepted") $application->update(["consDate" => $request->consDate]);

        if($request->status === "hoodwink") $application->update(["result" => $request->result, "advice" => $request->advice]);

        return $this->success(data: ["consultation" => $application]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application)
    {
        //
    }
}
