<?php

use App\Http\Controllers\Api\ContactController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('contacts', ContactController::class);

Route::get('uuid', function () {
    return Str::uuid();
});

Route::post('token', function (Request $request) {
    $contact = Contact::where('uuid', $request->input('uuid'))->exists();
    return $contact;
});
