<?php

use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\PhoneAuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RateTypeController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\PromoCodesController;
use App\Http\Controllers\Admin\ServiceDetailController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TripController;
use App\Models\Driver;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('phone-auth', [PhoneAuthController::class, 'index']);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/driverstatus', [DashboardController::class, 'driverstatus']);
    // Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('users', UserController::class);
    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::get('companyInfo', [BusinessSettingsController::class, 'companyInfo'])->name('companyInfo');
        Route::post('update-info',[BusinessSettingsController::class, 'updateInfo'])->name('update-info');
    });
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
            Route::get('index', [BrandController::class, 'index'])->name('brandlist');
            Route::get('eidt/{id}', [BrandController::class, 'edit'])->name('edit');
            Route::post('store', [BrandController::class, 'store'])->name('store');
            Route::post('update', [BrandController::class, 'update'])->name('update');
            Route::delete('delete', [BrandController::class, 'destroy'])->name('delete');
        });
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::get('index', [StateController::class, 'index'])->name('statelist');
            Route::get('edit/{id}', [StateController::class, 'edit'])->name('edit');
            Route::post('store', [StateController::class, 'store'])->name('store');
            Route::post('update', [StateController::class, 'update'])->name('update');
            Route::delete('delete', [StateController::class, 'destroy'])->name('delete');
        });
        Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
            Route::get('type', [ServiceTypeController::class, 'index'])->name('serviceType');
            Route::get('edit/{id}', [ServiceTypeController::class, 'edit'])->name('edit');
            Route::post('store', [ServiceTypeController::class, 'store'])->name('store');
            Route::post('update', [ServiceTypeController::class, 'update'])->name('update');
            Route::delete('delete', [ServiceTypeController::class, 'destroy'])->name('delete');
            Route::get('detail/index', [ServiceDetailController::class, 'index'])->name('add-index');
            Route::post('detail/store', [ServiceDetailController::class, 'store'])->name('detail-store');
            Route::get('detail/edit/{id}', [ServiceDetailController::class, 'edit'])->name('edit');
            Route::post('detail/update', [ServiceDetailController::class, 'update'])->name('detail-update');
        });
        Route::group(['prefix' => 'booking', 'as' => 'booking.'], function () {
            Route::get('index', [TripController::class, 'index'])->name('index');
            Route::get('today/booking', [TripController::class, 'todayBooking'])->name('today');
            Route::get('schedule', [TripController::class, 'schedule'])->name('schedule');
            Route::get('today/schedule', [TripController::class, 'todaySchedule'])->name('today-schedule');
            Route::get('assign/driver', [TripController::class, 'assignDriver'])->name('assign');
            // Route::post('store', [RateTypeController::class, 'store'])->name('store');
            // Route::get('eidt/{id}', [RateTypeController::class, 'edit'])->name('edit');
            // Route::post('update', [RateTypeController::class, 'update'])->name('update');
            // Route::delete('delete', [RateTypeController::class, 'destroy'])->name('delete');
        });
        Route::group(['prefix' => 'car', 'as' => 'car.'], function () {
            Route::get('index', [CarController::class, 'index'])->name('add-index');
            Route::get('edit/{id}', [CarController::class, 'edit'])->name('edit');
        });
        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('index', [CustomerController::class, 'index'])->name('index');
            Route::get('change/status', [CustomerController::class, 'status'])->name('status');
            Route::get('get/delete/{id}', [CustomerController::class, 'getdelete'])->name('getdelete');
            Route::delete('delete', [CustomerController::class, 'destroy'])->name('delete');
            Route::get('rides', [CustomerController::class, 'rides'])->name('rides');
            Route::get('detail/{id}', [CustomerController::class, 'detail'])->name('detail');
        });
        Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
            Route::get('index', [DriverController::class, 'index'])->name('index');
            Route::get('rides', [DriverController::class, 'rides'])->name('rides');
            Route::get('wallet', [DriverController::class, 'wallet'])->name('wallet');
            Route::get('detail/{id}', [DriverController::class, 'detail'])->name('detail');
            Route::post('varify', [DriverController::class, 'isVarifed'])->name('varify');
            Route::get('walletDetails/{id}', [DriverController::class, 'getWalletDetails'])->name('valletDetail');
            Route::get('pending/driver', [DriverController::class, 'getPendingDriver'])->name('pending');
        });
        Route::group(['prefix' => 'promoCodes', 'as' => 'promoCodes.'], function () {
            Route::get('index', [PromoCodesController::class, 'index'])->name('index');
            Route::post('store', [PromoCodesController::class, 'store'])->name('store');
            Route::get('edit/{id}', [PromoCodesController::class, 'edit'])->name('edit');
            Route::post('update', [PromoCodesController::class, 'update'])->name('update');
            Route::delete('delete', [PromoCodesController::class, 'destroy'])->name('delete');
        });
    });
});
