<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\LearningController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("register",[AuthController::class,"register"]);
Route::post("login",[AuthController::class,"login"]);
Route::get("not_found",fn()=>"hello");
Route::apiResource('sections', SectionController::class, ['only' => ['index']]);
Route::apiResource('questions', QuestionController::class, ['only' => ['index','show']]);
Route::middleware('auth:sanctum')->group(
    function () {
        Route::get("current_user",[AuthController::class,"user"]);
        Route::delete("logout",[AuthController::class,"logout"]);
        Route::put("user_update",[AuthController::class,"updateInfo"]);
        Route::put("password_update",[AuthController::class,"updatePassword"]);
        Route::apiResource('sections', SectionController::class, ['except' => ['index']]);
        Route::apiResource('questions', QuestionController::class, ['except' => ['index','show']]);
        Route::get("sections/{id}/review_questions",[LearningController::class,"reviewQuestions"]);
        Route::post("answer_reviews",[LearningController::class,"answerReviews"]);
        Route::post("answer_questions",[LearningController::class,"answerQuestions"]);
        Route::get("sections/{id}/new_questions",[LearningController::class,"newQuestions"]);
        Route::get("sections/{id}/test",[LearningController::class,"test"]);
});
