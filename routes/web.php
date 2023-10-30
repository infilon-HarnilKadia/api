<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MasterTruckController;
use App\Http\Controllers\MasterDriverController;
use App\Http\Controllers\MasterLocationController;
use App\Http\Controllers\MasterLoadingPointController;
use App\Http\Controllers\MasterTrailerController;
use App\Http\Controllers\MasterCustomerController;
use App\Http\Controllers\MasterStationController;
use App\Http\Controllers\MasterSupplierController;
use App\Http\Controllers\BookingOrderController;
use App\Http\Controllers\BordersController;
use App\Http\Controllers\CustomerChartController;
use App\Http\Controllers\DailyLocationController;
use App\Http\Controllers\FuelPurchaseOrderController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\LoadingController;
use App\Http\Controllers\ShortfallFormController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    // Artisan::call('cache:clear');
    // Artisan::call('view:clear');
    // Artisan::call('route:clear');
    // Artisan::call('clear-compiled');
    // Artisan::call('config:cache');

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');

    Artisan::call('optimize:clear');

    return "<pre>Cache is cleared.<script>setTimeout(function() {
      window.location.reload();
    }, 5000);</script>";
});


Route::get('/', function () {
    return view('login');
});
Route::get('/notification/get-list', [AdminController::class, 'notification']);

Route::post('/notification/save', [AdminController::class, 'savenot']);
Route::post('/auth/login', [LoginController::class, 'auth']);
Route::get('/auth/check', [LoginController::class, 'verify']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware(['MaintenanceHook'])->group(function () {
});

Route::middleware(['MaintenanceHook', 'SessionHook'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index']);

    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/admin/add', [AdminController::class, 'add']);
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit']);
    Route::get('/admin/lock/{id}', [AdminController::class, 'lock']);
    Route::post('/admin/save', [AdminController::class, 'save']);

    Route::get('/master-truck', [MasterTruckController::class, 'index']);
    Route::get('/master-truck/add', [MasterTruckController::class, 'add']);
    Route::post('/master-truck/save', [MasterTruckController::class, 'save']);
    Route::get('/master-truck/edit/{id}', [MasterTruckController::class, 'edit']);
    Route::get('/master-truck/delete/{id}', [MasterTruckController::class, 'delete']);

    Route::get('/master-driver', [MasterDriverController::class, 'index']);
    Route::get('/master-driver/add', [MasterDriverController::class, 'add']);
    Route::post('/master-driver/save', [MasterDriverController::class, 'save']);
    Route::get('/master-driver/edit/{id}', [MasterDriverController::class, 'edit']);
    Route::get('/master-driver/delete/{id}', [MasterDriverController::class, 'delete']);

    Route::get('/master-location', [MasterLocationController::class, 'index']);
    Route::get('/master-location/add', [MasterLocationController::class, 'add']);
    Route::post('/master-location/save', [MasterLocationController::class, 'save']);
    Route::get('/master-location/edit/{id}', [MasterLocationController::class, 'edit']);
    Route::get('/master-location/delete/{id}', [MasterLocationController::class, 'delete']);

    Route::get('/master-loading-point', [MasterLoadingPointController::class, 'index']);
    Route::get('/master-loading-point/add', [MasterLoadingPointController::class, 'add']);
    Route::post('/master-loading-point/save', [MasterLoadingPointController::class, 'save']);
    Route::get('/master-loading-point/edit/{id}', [MasterLoadingPointController::class, 'edit']);
    Route::get('/master-loading-point/delete/{id}', [MasterLoadingPointController::class, 'delete']);

    Route::get('/master-trailer', [MasterTrailerController::class, 'index']);
    Route::get('/master-trailer/add', [MasterTrailerController::class, 'add']);
    Route::post('/master-trailer/save', [MasterTrailerController::class, 'save']);
    Route::get('/master-trailer/edit/{id}', [MasterTrailerController::class, 'edit']);
    Route::get('/master-trailer/delete/{id}', [MasterTrailerController::class, 'delete']);

    Route::get('/master-customer', [MasterCustomerController::class, 'index']);
    Route::get('/master-customer/add', [MasterCustomerController::class, 'add']);
    Route::post('/master-customer/save', [MasterCustomerController::class, 'save']);
    Route::get('/master-customer/edit/{id}', [MasterCustomerController::class, 'edit']);
    Route::get('/master-customer/delete/{id}', [MasterCustomerController::class, 'delete']);

    Route::get('/master-station', [MasterStationController::class, 'index']);
    Route::get('/master-station/add', [MasterStationController::class, 'add']);
    Route::post('/master-station/save', [MasterStationController::class, 'save']);
    Route::get('/master-station/edit/{id}', [MasterStationController::class, 'edit']);
    Route::get('/master-station/delete/{id}', [MasterStationController::class, 'delete']);

    Route::get('/master-supplier', [MasterSupplierController::class, 'index']);
    Route::get('/master-supplier/add', [MasterSupplierController::class, 'add']);
    Route::post('/master-supplier/save', [MasterSupplierController::class, 'save']);
    Route::get('/master-supplier/edit/{id}', [MasterSupplierController::class, 'edit']);
    Route::get('/master-supplier/delete/{id}', [MasterSupplierController::class, 'delete']);

    Route::get('/booking-order', [BookingOrderController::class, 'index']);
    Route::get('/booking-order/ajax-data', [BookingOrderController::class, 'ajax']);
    Route::get('/booking-order/add', [BookingOrderController::class, 'add']);
    Route::get('/booking-order/edit/{id}', [BookingOrderController::class, 'edit']);
    Route::get('/booking-order/delete/{id}', [BookingOrderController::class, 'delete']);
    Route::post('/booking-order/save', [BookingOrderController::class, 'save']);

    Route::get('/fuel-purchase-order', [FuelPurchaseOrderController::class, 'index']);
    Route::get('/fuel-purchase-order/ajax-data', [FuelPurchaseOrderController::class, 'ajax']);
    Route::get('/fuel-purchase-order/add', [FuelPurchaseOrderController::class, 'add']);
    Route::get('/fuel-purchase-order/edit/{id}', [FuelPurchaseOrderController::class, 'edit']);
    Route::post('/fuel-purchase-order/save', [FuelPurchaseOrderController::class, 'save']);

    Route::get('/delivery-note', [DeliveryNoteController::class, 'index']);
    Route::get('/delivery-note/ajax-data', [DeliveryNoteController::class, 'ajax']);
    Route::get('/delivery-note/print/{id}', [DeliveryNoteController::class, 'print']);
    Route::get('/delivery-note/delete/{id}', [DeliveryNoteController::class, 'delete']);

    Route::get('/shortfall-form', [ShortfallFormController::class, 'index']);
    Route::post('/shortfall-form/save', [ShortfallFormController::class, 'save']);
    Route::post('/shortfall-form/data', [ShortfallFormController::class, 'data']);

    Route::get("/borders-border-details",[BordersController::class,'index']);
    Route::get("/borders-border-details/ajax-data",[BordersController::class,'ajax']);
    Route::get("/borders-border-details/edit/{id}",[BordersController::class,'edit']);
    Route::get("/broders-border-details/delete/{id}",[BordersController::class,'delete']);
    Route::post("/borders-border-details/save",[BordersController::class,'save']);

    Route::get("/borders-loading-chart",[LoadingController::class,"index"]);

    Route::get("/borders-customer-chart",[CustomerChartController::class,"index"]);

    Route::get('/accounts',[AccountController::class,"index"]);
    Route::get('/accounts/add/{type}',[AccountController::class,"add"]);
    Route::get('/accounts/edit-one/{type}/{id}',[AccountController::class,"editOne"]);
    Route::get('/accounts/edit/{type}/{id}',[AccountController::class,"edit"]);
    Route::get('/accounts/delete/{id}',[AccountController::class,"delete"]);
    Route::post('/accounts/save',[AccountController::class,"save"]);
    Route::post('/accounts/fetch',[AccountController::class,"fetch"]);
    Route::get('/accounts/print/{id}', [DeliveryNoteController::class, 'print']);
    Route::get("/accounts/ajax-data",[AccountController::class,'ajax']);


    Route::get('/daily-location',[DailyLocationController::class,"index"]);
    Route::get('/daily-location/add',[DailyLocationController::class,"add"]);
    Route::post('/daily-location/fetch',[DailyLocationController::class,"fetchData"]);

    Route::get('/password', [LoginController::class, 'password']);
    Route::post('/password', [LoginController::class, 'password_update']);
    Route::get('/password/icons', [LoginController::class, 'icons']);
});
