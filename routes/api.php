<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ConsultantProblemsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(\App\Http\Middleware\token::class)->group(function (){
    Route::middleware(\App\Http\Middleware\admin::class)->group(function (){
        Route::post('/regions', [RegionController::class, 'store']);
        Route::put('/regions/{region}', [RegionController::class, 'update']);
        Route::delete('/regions/{region}', [RegionController::class, 'destroy']);

        Route::post('/regions/{region}/organizations', [OrganizationController::class, 'store']);
        Route::put('/regions/{region}/organizations/{organization}', [OrganizationController::class, 'update']);
        Route::delete('/regions/{region}/organizations/{organization}', [OrganizationController::class, 'destroy']);

        Route::post('/regions/{region}/organizations/{organization}/consultants', [ConsultantController::class, 'store']);
        Route::put('/regions/{region}/organizations/{organization}/consultants/{user}', [ConsultantController::class, 'update']);
        Route::delete('/regions/{region}/organizations/{organization}/consultants/{user}', [ConsultantController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        Route::post('/categories/{category}/problems', [ProblemController::class, 'store']);
        Route::put('/categories/{category}/problems/{problem}', [ProblemController::class, 'update']);
        Route::delete('/categories/{category}/problems/{problem}', [ProblemController::class, 'destroy']);
    });

    Route::get('/consultations', [ApplicationController::class, 'index']);
    Route::get('/consultations/{application}', [ApplicationController::class, 'show']);
    Route::post('/problems', [ConsultantProblemsController::class, 'store']);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::post('/consultations', [ApplicationController::class, 'store']);
Route::patch('/consultations/{application}', [ApplicationController::class, 'update']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/regions', [RegionController::class, 'index']);
Route::get('/regions/{region}/organizations', [OrganizationController::class, 'index']);
Route::get('/regions/{region}/organizations/{organization}/consultants', [ConsultantController::class, 'index']);
Route::get('/categories/{category}/problems', [ProblemController::class, 'index']);
