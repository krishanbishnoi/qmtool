<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['namespace' => 'Api'], function () {
//     Route::get('get-countries', function(){
//         dd("here");
//     });
// });

Route::prefix('qm-tool')->group(function() {
Route::post('client-qa','AllocationController@clientQa');

});
Route::post('updated_lob','AllocationController@updated_lob');
