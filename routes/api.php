<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/login", [AdminController::class, "login"]);
Route::post('/refreshtoken', [AdminController::class, "refresh_token"]);
Route::group(["middleware" => "checkToken"],function(){
    Route::post('/dashboard', [AdminController::class, "dashboard"]);


    Route::post('/truckmaster', [AdminController::class, "truckMaster"]);
    Route::post('/addtruckmaster', [AdminController::class, "addtruckMaster"]);
    Route::post('/deletetruckmaster', [AdminController::class, "deletetruckMaster"]);
    Route::post('/showtruckmaster',[AdminController::class,"showtruckmaster"]);
    Route::post("/edittruckmaster",[AdminController::class,"edittruckmaster"]);


    Route::post('/drivermaster',[AdminController::class,'drivermaster']);
    Route::post('/adddrivermaster',[AdminController::class,'adddrivermaster']);
    Route::post('/editdrivermaster',[AdminController::class,'editdrivermaster']);
    Route::post('/showdrivermaster',[AdminController::class,'showdrivermaster']);
    Route::post('/deletedrivermaster',[AdminController::class,'deletedrivermaster']);


    Route::post('/locationmaster',[AdminController::class,'locationmaster']);
    Route::post('/addlocationmaster',[AdminController::class,'addlocationmaster']);
    Route::post('/showlocationmaster',[AdminController::class,'showlocationmaster']);
    Route::post('/editlocationmaster',[AdminController::class,'editlocationmaster']);
    Route::post('/deletelocationmaster',[AdminController::class,'deletelocationmaster']);

    Route::post('/trailermaster',[AdminController::class,'trailermaster']);
    Route::post('/addtrailermaster',[AdminController::class,'addtrailermaster']);
    Route::post('/deletetrailermaster',[AdminController::class,'deletetrailermaster']);
    Route::post('/showtrailermaster',[AdminController::class,'showtrailermaster']);
    Route::post('/edittrailermaster',[AdminController::class,'edittrailermaster']);

    Route::post('/bookingorder',[AdminController::class,'bookingorder']);
    Route::post('/showbookingorder',[AdminController::class,'showbookingorder']);
    Route::post('/deletebookingorder',[AdminController::class,'deletebookingorder']);
    Route::post('/editbookingorder',[AdminController::class,'editbookingorder']);
    Route::post('/addbookingorder',[AdminController::class,'addbookingorder']);

    //DPz0ne
    // Route::post('/bookingCus',[AdminController::class,'bookingCus']);
    // Route::post('/bookingLoading',[AdminController::class,'bookingLoading']);
    
    Route::post('/bookingTruck',[AdminController::class,'bookingTruck']); 
    Route::post('/bookingTrailer',[AdminController::class,'bookingTrailer']);
    Route::post('/bookingDriver',[AdminController::class,'bookingDriver']);

    //fuelpurchaseorder
    Route::post('/fuelpurchaseorder',[AdminController::class,'fuelpurchaseorder']);
    Route::post('/addfuelpurchaseorder',[AdminController::class,'addfuelpurchaseorder']);
    Route::post('/deletefuelpurchaseorder',[AdminController::class,'deletefuelpurchaseorder']);
    //end fuelpurchaseorder
  

    //@DPz0ne
    Route::post('/showfuelpurchaseorder',[AdminController::class,'showfuelpurchaseorder']);
    Route::post('/editfuelpurchaseorder',[AdminController::class,'editfuelpurchaseorder']);
    Route::post('/showdeliverynote',[AdminController::class,'showdeliverynote']);
    Route::post('/shortfallform',[AdminController::class,'shortfallform']);
    //@DPz0ne
    
    Route::post('/borderDetails',[AdminController::class,'borderDetails']);
    Route::post('/showborderDetails',[AdminController::class,'showborderDetails']);
    Route::post('/editborderDetails',[AdminController::class,'editborderDetails']);
    Route::post('/deleteborderDetails',[AdminController::class,'deleteborderDetails']);
    Route::post('/account',[AdminController::class,'account']);
});

// Route::post('/logout',[AdminController::class,"logout"]);