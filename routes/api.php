<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\AuthController;


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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
  //  return $request->user();
//});
//Auth routes

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');;


Route::group(['middleware' => ['auth:sanctum']], function () {
Route::apiResource('user', UserController::class);
Route::apiResource('organization', OrganizationController::class);
Route::post('/vacancy-book', [VacancyController::class, 'vacancy_book']);
Route::post('/vacancy-unbook', [VacancyController::class, 'vacancy_unbook']);
Route::apiResource('vacancy', VacancyController::class);
Route::get('stats/organization', [OrganizationController::class, 'organizations_count']);
Route::get('stats/vacancy', [VacancyController::class, 'vacancies_count']);
Route::get('stats/user', [UserController::class, 'users_roles_count']);
});

Route::fallback( [AuthController::class, 'fallback']);
