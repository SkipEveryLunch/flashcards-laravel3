<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CommentTypeController;
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
Route::apiResource('series', SeriesController::class, ['only' => ['index']]);
Route::apiResource('comment_types', CommentTypeController::class, ['only' => ['index']]);
Route::middleware('auth:sanctum')->group(
    function () {
        Route::get("current_user",[AuthController::class,"user"]);
        Route::delete("logout",[AuthController::class,"logout"]);
        Route::put("user_update",[AuthController::class,"updateInfo"]);
        Route::put("password_update",[AuthController::class,"updatePassword"]);
        Route::apiResource('sections', SectionController::class, ['except' => ['index']]);
        Route::apiResource('questions', QuestionController::class);
        Route::get("sections/{id}/review_questions",[LearningController::class,"reviewQuestions"]);
        Route::post("answer_reviews",[LearningController::class,"answerReviews"]);
        Route::post("sections/{sectionId}/answer_questions",[LearningController::class,"answerQuestions"]);
        Route::get("sections/{id}/new_questions",[LearningController::class,"newQuestions"]);
        Route::get("sections/{id}/test",[LearningController::class,"test"]);
        Route::get("questions_comments/{questionId}",[CommentController::class,"show"]);
        Route::get("questions_several_comments/{questionId}",[CommentController::class,"showSeveral"]);
        Route::post("questions_comments/{questionId}",[CommentController::class,"store"]);
        Route::put("questions_comments/{questionId}",[CommentController::class,"update"]);
        Route::delete("questions_comments/{questionId}",[CommentController::class,"destroy"]);
        Route::get("messages",[MessageController::class,"show"]);
        Route::put("messages/{messageId}",[MessageController::class,"confirm"]);
        Route::delete("comments/{commentId}",[CommentController::class,"destroyForAdmin"]);
});
